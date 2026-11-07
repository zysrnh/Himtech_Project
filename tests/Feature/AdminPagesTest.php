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
        $response->assertSee('Tambah Organisasi Manual');
        $response->assertSee('modal-add-organization');
    }

    public function test_admin_penerima_can_store_organization_manually(): void
    {
        $admin = User::factory()->create([
            'is_super_admin' => true,
            'admin_role' => 'admin_penerima',
        ]);

        $response = $this->actingAs($admin)->post(route('admin.organizations.store'), [
            'name' => 'Himpunan Test Penerima',
            'code' => 'HTEST',
            'description' => 'Deskripsi test manual',
            'staff_name' => 'Staff Test',
            'staff_username' => 'staff_htest',
            'staff_email' => 'staff_htest@test.local',
            'staff_phone' => '081234567890',
            'staff_password' => 'Password123!',
        ]);

        $response->assertRedirect(route('admin.organizations.index'));
        $this->assertDatabaseHas('organizations', [
            'code' => 'HTEST',
            'name' => 'Himpunan Test Penerima',
        ]);
        $this->assertDatabaseHas('users', [
            'username' => 'staff_htest',
            'email' => 'staff_htest@test.local',
        ]);
        $this->assertDatabaseHas('organization_user', [
            'role' => 'admin_organisasi',
            'is_active' => true,
        ]);
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
