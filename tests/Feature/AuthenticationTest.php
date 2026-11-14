<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

final class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_all_authentication_pages_render_the_same_appearance_controls(): void
    {
        app()->setLocale('ar');

        foreach (['/login', '/register', '/forgot-password', '/reset-password/'.str_repeat('a', 64)] as $path) {
            $this->get($path)->assertOk()->assertSee('dir="rtl"', false)
                ->assertSee('data-appearance-mode="light"', false)
                ->assertSee('data-appearance-mode="dark"', false)
                ->assertSee('data-appearance-mode="system"', false);
            if ($path !== '/forgot-password') {
                $this->get($path)->assertSee('data-password-toggle', false)->assertSee('data-password-eye-off', false);
            }
        }
    }

    public function test_registration_and_sign_in_ignore_submitted_privileges(): void
    {
        $this->post('/register', [
            'name' => 'New member', 'email' => 'MEMBER@example.test', 'password' => 'test-password-123',
            'password_confirmation' => 'test-password-123', 'role' => 'Super Admin', 'is_super_admin' => true,
        ])->assertRedirect('/login');

        $user = User::where('email', 'member@example.test')->sole();
        $this->assertArrayNotHasKey('role', $user->getAttributes());
        $this->assertArrayNotHasKey('is_super_admin', $user->getAttributes());
        $this->post('/login', ['email' => $user->email, 'password' => 'test-password-123'])->assertRedirect('/account');
        $this->assertAuthenticatedAs($user);
        $this->post('/logout')->assertRedirect('/login');
        $this->assertGuest();
    }

    public function test_reset_tokens_are_single_use_and_expire(): void
    {
        $user = User::factory()->create(['remember_token' => 'old-token']);
        $token = Password::createToken($user);
        $data = [
            'token' => $token, 'email' => $user->email,
            'password' => 'replacement-password', 'password_confirmation' => 'replacement-password',
        ];
        $this->post('/reset-password', $data)->assertRedirect('/login');
        $this->assertNotSame('old-token', $user->refresh()->getRememberToken());
        $this->post('/reset-password', $data)->assertSessionHasErrors('email')->assertSessionMissing('_old_input.token');
        $data['token'] = Password::createToken($user);
        $this->travel(61)->minutes();
        $this->post('/reset-password', $data)->assertSessionHasErrors('email');
    }

    public function test_log_mail_transport_returns_the_same_unavailable_message_for_every_address(): void
    {
        config(['mail.default' => 'log']);
        $user = User::factory()->create();

        foreach ([$user->email, 'unknown@example.test'] as $email) {
            $this->from('/forgot-password')->post('/forgot-password', compact('email'))
                ->assertRedirect('/forgot-password')
                ->assertSessionHas('status', 'Password reset email is currently unavailable. Contact the site operator.');
        }

        $this->assertDatabaseCount('password_reset_tokens', 0);
    }
}
