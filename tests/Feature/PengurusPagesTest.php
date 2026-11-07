<?php

namespace Tests\Feature;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PengurusPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_metode_pembayaran_page_can_be_rendered(): void
    {
        $organization = Organization::create([
            'name' => 'HIMTECH Testing',
            'code' => 'HIMTECH',
            'email' => 'himtech@test.com',
            'status' => 'active',
            'is_midtrans_enabled' => true,
        ]);

        $user = User::factory()->create();

        $organization->users()->attach($user->id, [
            'role' => 'bendahara',
            'is_active' => true,
        ]);

        $response = $this
            ->actingAs($user)
            ->withSession(['active_organization_id' => $organization->id])
            ->get(route('pengurus.pembayaran.index'));

        $response->assertStatus(200);
        $response->assertSee('Metode Pembayaran Kas');
        $response->assertSee('Saluran Pembayaran Gateway Otomatis');
    }

    public function test_buku_kas_page_can_be_rendered(): void
    {
        $organization = Organization::create([
            'name' => 'HIMTECH Testing',
            'code' => 'HIMTECH',
            'email' => 'himtech@test.com',
            'status' => 'active',
            'is_midtrans_enabled' => true,
        ]);

        $user = User::factory()->create();

        $organization->users()->attach($user->id, [
            'role' => 'bendahara',
            'is_active' => true,
        ]);

        $response = $this
            ->actingAs($user)
            ->withSession(['active_organization_id' => $organization->id])
            ->get(route('pengurus.buku-kas.index'));

        $response->assertStatus(200);
        $response->assertSee('Buku Kas');
        $response->assertSee('Mutasi Finansial');
        $response->assertSee('Catat Transaksi Baru');
    }
}
