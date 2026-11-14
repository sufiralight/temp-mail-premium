<?php

namespace Tests\Feature;

use App\Installation\InstallerDatabase;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

final class InstallerTest extends TestCase
{
    private string $storage;

    protected function setUp(): void
    {
        parent::setUp();

        $this->storage = sys_get_temp_dir().DIRECTORY_SEPARATOR.'goal-installer-'.bin2hex(random_bytes(12));
        File::makeDirectory($this->storage.'/app/private/installer', 0700, true);
        $this->app->useStoragePath($this->storage);
        $this->withServerVariables(['REMOTE_ADDR' => '127.0.0.1', 'SERVER_ADDR' => '127.0.0.1']);
    }

    protected function tearDown(): void
    {
        File::deleteDirectory($this->storage);

        parent::tearDown();
    }

    public function test_installer_is_closed_without_an_operator_gate(): void
    {
        $this->get('http://localhost:8000/install')->assertNotFound();
    }

    public function test_local_operator_can_open_either_loopback_hostname(): void
    {
        $this->enableLocalGate();

        foreach (['localhost', '127.0.0.1'] as $host) {
            $this->get("http://{$host}:8000/")->assertRedirect('/install');
            $this->get("http://{$host}:8000/install")->assertOk()->assertSee('Database Configuration');
        }
    }

    public function test_proxy_headers_and_direct_requirements_access_are_rejected(): void
    {
        $this->enableLocalGate();

        $this->get('http://localhost:8000/install/requirements')->assertNotFound();
        $this->get('http://localhost:8000/install', ['X-Forwarded-For' => '127.0.0.1'])->assertNotFound();
    }

    public function test_failed_test_invalidates_verification_and_does_not_flash_password(): void
    {
        $this->enableLocalGate();
        $this->mock(InstallerDatabase::class)->shouldReceive('verify')->once()->andReturn('Authentication failed.');

        $response = $this->post('http://localhost:8000/install', [
            'engine' => 'mysql',
            'host' => '127.0.0.1',
            'port' => 3306,
            'database' => 'fixture',
            'username' => 'fixture',
            'password' => 'fixture-secret',
        ]);

        $response->assertRedirect()->assertSessionHasErrors(['connection'])
            ->assertSessionMissing('_old_input.password')->assertSessionMissing('installer.database');
    }

    public function test_engine_choices_and_shared_appearance_are_available(): void
    {
        $this->enableLocalGate();

        $this->get('http://localhost:8000/install')->assertOk()
            ->assertSee('name="engine"', false)
            ->assertSee('value="pgsql" disabled', false)
            ->assertSee('value="sqlite" disabled', false)
            ->assertSee('data-appearance-mode="light"', false)
            ->assertSee('data-appearance-mode="dark"', false)
            ->assertSee('data-appearance-mode="system"', false);
    }

    public function test_selected_engine_reaches_verification_and_an_unsupported_engine_clears_it(): void
    {
        $this->enableLocalGate();
        $fixture = ['host' => 'localhost', 'port' => 3306, 'database' => 'fixture', 'username' => 'fixture'];
        $database = $this->mock(InstallerDatabase::class);

        foreach (['mysql', 'mariadb'] as $engine) {
            $database->shouldReceive('verify')->once()->with([
                'engine' => $engine, 'host' => '127.0.0.1', 'port' => 3306,
                'database' => 'fixture', 'username' => 'fixture', 'password' => '',
            ])->andReturnNull();
            $this->post('http://localhost:8000/install', ['engine' => $engine] + $fixture)
                ->assertRedirect('/install/requirements');
            $this->get('http://localhost:8000/install/requirements')->assertOk();
        }

        $this->post('http://localhost:8000/install', ['engine' => 'sqlite'] + $fixture)
            ->assertSessionHasErrors('engine')->assertSessionMissing('installer.database');
        $this->get('http://localhost:8000/install/requirements')->assertNotFound();
    }

    private function enableLocalGate(): void
    {
        File::put($this->storage.'/app/private/installer/enable.json', '{"mode":"local"}');
    }
}
