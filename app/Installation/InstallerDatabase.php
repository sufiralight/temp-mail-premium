<?php

namespace App\Installation;

use PDO;
use PDOException;

class InstallerDatabase
{
    public function connect(#[\SensitiveParameter] array $settings): PDO
    {
        return new PDO(
            'mysql:host='.$settings['host'].';port='.$settings['port'].';dbname='.$settings['database'].';charset=utf8mb4',
            $settings['username'],
            $settings['password'],
            [PDO::ATTR_TIMEOUT => 3, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION],
        );
    }

    public function verify(#[\SensitiveParameter] array $settings): ?string
    {
        if (! in_array('mysql', PDO::getAvailableDrivers(), true)) {
            return 'Enable PDO MySQL in the hosting control panel.';
        }

        $timeout = ini_set('mysqlnd.net_read_timeout', '3');
        try {
            $connection = $this->connect($settings);
            $product = $this->serverProduct($connection);
            if ($product === null) {
                return 'The server product could not be verified. Ask your hosting provider whether this endpoint runs MySQL or MariaDB.';
            }

            if ($product !== $settings['engine']) {
                return $product === 'mariadb'
                    ? 'This endpoint runs MariaDB. Select MariaDB to test this connection.'
                    : 'This endpoint runs MySQL. Select MySQL to test this connection.';
            }

            if ($connection->query('SELECT DATABASE()')->fetchColumn() !== $settings['database']) {
                return 'The selected database could not be verified.';
            }

            if ($connection->query('SHOW FULL TABLES')->fetchColumn() !== false) {
                return 'This database already contains tables or views. Select an empty database or contact the operator for recovery.';
            }

            return null;
        } catch (PDOException $exception) {
            return match ((int) ($exception->errorInfo[1] ?? 0)) {
                1044, 1045 => 'Authentication failed or the account lacks database access.',
                1049 => 'The database is unavailable. Verify its name in the hosting control panel.',
                2002, 2003 => stripos($exception->getMessage(), 'timed out') !== false
                    ? 'The database connection timed out.'
                    : 'The database host refused or could not accept the connection.',
                2006, 2013 => 'The database connection was interrupted or timed out.',
                default => 'The database could not be verified. Check the connection settings and account permissions.',
            };
        } finally {
            if ($timeout !== false) {
                ini_set('mysqlnd.net_read_timeout', $timeout);
            }
        }
    }

    private function serverProduct(PDO $connection): ?string
    {
        $server = $connection->query('SELECT VERSION(), @@version_comment')->fetch(PDO::FETCH_NUM);
        $aria = $connection->query("SELECT ENGINE FROM information_schema.ENGINES WHERE ENGINE = 'Aria'")->fetchColumn();
        $identity = implode(' ', $server ?: []);

        if ($aria !== false || stripos($identity, 'MariaDB') !== false) {
            return 'mariadb';
        }

        return preg_match('/\b(?:MySQL|Percona Server)\b/i', $identity) === 1 ? 'mysql' : null;
    }
}
