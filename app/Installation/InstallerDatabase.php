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
            $version = (string) $connection->query('SELECT VERSION()')->fetchColumn();
            $isMariaDb = stripos($version, 'MariaDB') !== false;
            if ($isMariaDb !== ($settings['engine'] === 'mariadb')) {
                return 'The server reports a different engine. Select the matching engine and test again.';
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
}
