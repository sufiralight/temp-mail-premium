<?php

namespace Tests\Unit;

use App\Installation\InstallerDatabase;
use PDO;
use PDOStatement;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class InstallerDatabaseTest extends TestCase
{
    #[DataProvider('products')]
    public function test_product_detection(string $selection, string $version, string $comment, string|false $aria, ?string $expected): void
    {
        if (! in_array('mysql', PDO::getAvailableDrivers(), true)) {
            $this->markTestSkipped('PDO MySQL required.');
        }

        $metadata = $this->createMock(PDOStatement::class);
        $metadata->method('fetch')->willReturn([$version, $comment]);
        $engines = $this->createMock(PDOStatement::class);
        $engines->method('fetchColumn')->willReturn($aria);
        $database = $this->createMock(PDOStatement::class);
        $database->method('fetchColumn')->willReturn('fixture');
        $tables = $this->createMock(PDOStatement::class);
        $tables->method('fetchColumn')->willReturn(false);

        $pdo = $this->createMock(PDO::class);
        $pdo->method('query')->willReturnMap([
            ['SELECT VERSION(), @@version_comment', null, $metadata],
            ["SELECT ENGINE FROM information_schema.ENGINES WHERE ENGINE = 'Aria'", null, $engines],
            ['SELECT DATABASE()', null, $database],
            ['SHOW FULL TABLES', null, $tables],
        ]);
        $installer = $this->getMockBuilder(InstallerDatabase::class)->onlyMethods(['connect'])->getMock();
        $installer->method('connect')->willReturn($pdo);

        $this->assertSame($expected, $installer->verify(['engine' => $selection, 'database' => 'fixture']));
    }

    public static function products(): array
    {
        return [
            'MySQL' => ['mysql', '8.4.0', 'MySQL Community Server', false, null],
            'MariaDB with normalized version' => ['mariadb', '10.11.0', 'Distribution build', 'Aria', null],
            'mismatch' => ['mysql', '10.11.0', 'Distribution build', 'Aria', 'This endpoint runs MariaDB. Select MariaDB to test this connection.'],
        ];
    }
}
