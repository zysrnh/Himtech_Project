<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Tabel Metode Pembayaran Mandiri Organisasi (Rekening Bank, E-Wallet, QRIS Statis)
        Schema::create('organization_payment_methods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->string('type')->default('bank'); // bank, ewallet, qris
            $table->string('channel_name'); // misal: Bank BCA, Bank Mandiri, DANA, GoPay, QRIS Ormawa
            $table->string('account_number')->nullable(); // no rek / no hp
            $table->string('account_holder')->nullable(); // a.n. pemilik rekening
            $table->string('qris_image_path')->nullable(); // upload gambar QRIS statis
            $table->text('instructions')->nullable(); // catatan petunjuk transfer
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. Tambahkan kolom Midtrans & Snap Token pada dues_bills
        Schema::table('dues_bills', function (Blueprint $table) {
            $table->string('snap_token')->nullable()->after('payment_method');
            $table->string('midtrans_order_id')->nullable()->after('snap_token');
            $table->string('midtrans_payment_type')->nullable()->after('midtrans_order_id');
            $table->string('payment_channel')->nullable()->after('midtrans_payment_type');
        });

        // 3. Tambahkan saklar toggle Midtrans pada organizations
        Schema::table('organizations', function (Blueprint $table) {
            $table->boolean('is_midtrans_enabled')->default(true)->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->dropColumn('is_midtrans_enabled');
        });

        Schema::table('dues_bills', function (Blueprint $table) {
            $table->dropColumn([
                'snap_token',
                'midtrans_order_id',
                'midtrans_payment_type',
                'payment_channel',
            ]);
        });

        Schema::dropIfExists('organization_payment_methods');
    }
};
