<?php

namespace App\Installation;

use Illuminate\Http\Request;

final class InstallationGate
{
    public function authorize(Request $request): array
    {
        abort_if(is_file(storage_path('app/private/installer/installed')), 404);
        abort_if(collect($request->headers->keys())->contains(fn (string $key) => $key === 'forwarded' || str_starts_with($key, 'x-forwarded-')), 404);

        $path = storage_path('app/private/installer/enable.json');
        $contents = ! is_link($path) && is_file($path) ? @file_get_contents($path, length: 4097) : false;
        $gate = is_string($contents) && strlen($contents) <= 4096 ? json_decode($contents, true) : null;
        abort_unless(is_array($gate), 404);

        $peer = $request->server('REMOTE_ADDR');
        $host = strtolower((string) $request->server('HTTP_HOST'));
        $loopback = ['127.0.0.1', '::1'];
        if (($gate['mode'] ?? null) === 'local') {
            abort_unless(app()->environment('local', 'testing')
                && in_array($peer, $loopback, true)
                && (PHP_SAPI === 'cli-server' || in_array($request->server('SERVER_ADDR'), $loopback, true))
                && preg_match('/\A(?:localhost|127\.0\.0\.1)(?::\d{1,5})?\z/', $host), 404);

            return ['host' => '127.0.0.1', 'port' => null, 'scope' => hash('sha256', $contents)];
        }

        $domain = $gate['host'] ?? null;
        $operator = $gate['operator_ip'] ?? null;
        $databaseHost = $gate['database_host'] ?? null;
        $port = $gate['database_port'] ?? 3306;
        abort_unless(($gate['mode'] ?? null) === 'production'
            && is_string($domain) && filter_var($domain, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME)
            && in_array($host, [strtolower($domain), strtolower($domain).':443'], true)
            && is_string($operator) && filter_var($operator, FILTER_VALIDATE_IP)
            && is_string($peer) && inet_pton($peer) === inet_pton($operator)
            && in_array(strtolower((string) $request->server('HTTPS')), ['on', '1'], true)
            && is_string($databaseHost) && filter_var($databaseHost, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME)
            && is_int($port) && $port >= 1 && $port <= 65535, 404);

        return ['host' => strtolower($databaseHost), 'port' => $port, 'scope' => hash('sha256', $contents)];
    }
}
