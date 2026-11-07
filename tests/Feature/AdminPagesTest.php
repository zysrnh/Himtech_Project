<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_can_render_dashboard(): void
    {
        $admin = User::factory()->create([
            'is_super_admin' => true,
            'admin_role' => 'super_admin',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Overview Sistem Kasly');
    }

    public function test_super_admin_can_render_organizations_index(): void
    {
        $admin = User::factory()->create([
            'is_super_admin' => true,
            'admin_role' => 'super_admin',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.organizations.index'));
        $response->assertStatus(200);
        $response->assertSee('Manajemen Organisasi');
    }

    public function test_admin_penerima_can_render_organizations_index(): void
    {
        $admin = User::factory()->create([
            'is_super_admin' => true,
            'admin_role' => 'admin_penerima',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.organizations.index'));
        $response->assertStatus(200);
        $response->assertSee('Pengajuan Organisasi Masuk');
    }

    public function test_super_admin_can_render_organizations_create(): void
    {
        $admin = User::factory()->create([
            'is_super_admin' => true,
            'admin_role' => 'super_admin',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.organizations.create'));
        $response->assertStatus(200);
        $response->assertSee('Tambah Organisasi Baru');
    }

    public function test_super_admin_can_render_admins_index(): void
    {
        $admin = User::factory()->create([
            'is_super_admin' => true,
            'admin_role' => 'super_admin',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.admins.index'));
        $response->assertStatus(200);
        $response->assertSee('Kelola Administrator Kasly');
    }

    public function test_super_admin_can_render_profile(): void
    {
        $admin = User::factory()->create([
            'is_super_admin' => true,
            'admin_role' => 'super_admin',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.profile'));
        $response->assertStatus(200);
        $response->assertSee('Pengaturan Profil Administrator');
    }
}
