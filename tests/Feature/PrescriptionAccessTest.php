<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

class PrescriptionAccessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'user']);
        Role::firstOrCreate(['name' => 'visitor']);
    }

    /** @test */
    public function guest_is_redirected_to_login()
    {
        $this->get('/prescriptions')->assertRedirect('/login');
    }

    /** @test */
    public function visitor_role_is_redirected()
    {
        $visitor = User::factory()->create();
        $visitor->assignRole('visitor');
        $this->actingAs($visitor);
        $resp = $this->get('/prescriptions');
        $resp->assertStatus(302);
        $resp->assertRedirect(route('visitor.home.ru'));
    }

    /** @test */
    public function user_role_can_access()
    {
        $user = User::factory()->create();
        $user->assignRole('user');
        $this->actingAs($user);
        $this->get('/prescriptions')->assertOk();
    }

    /** @test */
    public function admin_role_can_access()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $this->actingAs($admin);
        $this->get('/prescriptions')->assertOk();
    }
}
