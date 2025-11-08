<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    protected User $manager;

    protected function setUp(): void
    {
        parent::setUp();
        $role = Role::create(['name' => 'manager']);

        $this->manager = User::factory()->create();
        $this->manager->assignRole($role);
    }

    /** @test */
    public function guest_cannot_access_admin_panel()
    {
        $response = $this->get('/admin/tickets');

        $response->assertStatus(302)->assertRedirect('/login');
    }

    /** @test */
    public function manager_can_access_admin_panel()
    {
        $response = $this->actingAs($this->manager)->get('/admin/tickets');

        $response->assertStatus(200);
    }
}
