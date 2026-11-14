<?php

namespace App\Http\Controllers;

use App\Installation\InstallationGate;
use App\Installation\InstallerDatabase;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

final class InstallerController extends Controller
{
    public function show(Request $request, InstallationGate $gate): View
    {
        $access = $gate->authorize($request);

        $request->session()->forget('installer.database');

        return view('installation.database', ['access' => $access]);
    }

    public function verify(Request $request, InstallationGate $gate, InstallerDatabase $database): RedirectResponse
    {
        $access = $gate->authorize($request);
        $request->session()->forget('installer.database');
        $input = $request->only(['engine', 'host', 'port', 'database', 'username', 'password']);
        $validator = Validator::make($input, [
            'engine' => ['required', 'string', Rule::in(['mysql', 'mariadb'])],
            'host' => ['required', 'string', 'max:253', 'regex:/\A[a-zA-Z0-9.-]+\z/'],
            'port' => ['required', 'integer', 'between:1,65535'],
            'database' => ['required', 'string', 'regex:/\A[a-zA-Z0-9_-]{1,64}\z/'],
            'username' => ['required', 'string', 'max:128', 'not_regex:/[\x00-\x1f\x7f]/'],
            'password' => ['nullable', 'string', 'max:1024', 'not_regex:/[\x00-\x1f\x7f]/'],
        ]);
        $fields = array_filter($request->only(['engine', 'host', 'port', 'database', 'username']), fn (mixed $value) => is_scalar($value) && strlen((string) $value) <= 253);
        if ($validator->fails()) {
            return redirect()->route('install.database')->withErrors($validator)->withInput($fields);
        }

        $settings = $validator->validated();
        $settings['host'] = strtolower($settings['host']);
        $settings['port'] = (int) $settings['port'];
        $settings['password'] ??= '';
        if ($access['local'] && $access['host'] === '127.0.0.1' && $settings['host'] === 'localhost') {
            $settings['host'] = '127.0.0.1';
        }
        if ($settings['host'] !== $access['host'] || ($access['port'] !== null && $settings['port'] !== $access['port'])) {
            return redirect()->route('install.database')->withErrors(['host' => 'Use the database endpoint authorized by your operator.'])->withInput($fields);
        }

        $error = $database->verify($settings);
        if ($error !== null) {
            return redirect()->route('install.database')->withErrors(['connection' => $error])->withInput($fields);
        }

        $request->session()->regenerate();
        $request->session()->put('installer.database', ['scope' => $access['scope'], 'engine' => $settings['engine'], 'expires' => now()->addMinutes(15)->timestamp]);

        return redirect()->route('install.requirements');
    }

    public function requirements(Request $request, InstallationGate $gate): View
    {
        $access = $gate->authorize($request);
        $verification = $request->session()->get('installer.database');
        abort_unless(is_array($verification) && $verification['scope'] === $access['scope'] && $verification['expires'] > now()->timestamp, 404);

        return view('installation.requirements', ['engine' => $verification['engine'] === 'mariadb' ? 'MariaDB' : 'MySQL', 'checks' => [
            'PHP '.PHP_VERSION => version_compare(PHP_VERSION, '8.3', '>='),
            'PDO MySQL' => in_array('mysql', \PDO::getAvailableDrivers(), true),
            'OpenSSL' => extension_loaded('openssl'),
            'Session storage' => is_writable(storage_path('framework/sessions')),
            'Cache storage' => is_writable(storage_path('framework/cache/data')),
            'Environment directory' => is_writable(base_path()) && ! is_link(app()->environmentFilePath()) && ! app()->configurationIsCached(),
            'Vite build' => is_file(public_path('build/manifest.json')),
        ]]);
    }
}
