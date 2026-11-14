<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

final class AdminConsoleTest extends TestCase
{
    use RefreshDatabase;

    public function test_direct_access_requires_an_administrative_role(): void
    {
        $this->get('/admin')->assertRedirect('/login');
        $this->actingAs(User::factory()->create())->get('/admin')->assertForbidden();
        $this->get('/admin/users')->assertForbidden();

        foreach (['admin', 'super_admin'] as $role) {
            $this->actingAs(User::factory()->create(['role' => $role]))
                ->get('/admin')->assertOk()->assertSee('Total users')
                ->assertSee('data-appearance-dropdown', false)->assertSee('aria-current="page"', false);
        }
    }

    public function test_users_are_filtered_paginated_and_have_no_mutation_endpoint(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']));
        User::factory()->count(21)->create(['name' => 'Matching Account']);
        User::factory()->create(['name' => 'Excluded Account']);

        $this->get('/admin/users?q=Matching&role=member')->assertOk()->assertSee('Matching Account')->assertDontSee('Excluded Account')->assertSee('page=2');
        $this->get('/admin/users?q=missing')->assertOk()->assertSee('No users match your filters.');
        $this->post('/admin/users', ['role' => 'super_admin'])->assertMethodNotAllowed();
    }

    public function test_authorized_request_gets_an_actionable_missing_schema_response(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']));
        Schema::dropIfExists('users');

        $this->get('/admin')->assertServiceUnavailable()->assertSee('Account schema is incomplete');
    }
}
