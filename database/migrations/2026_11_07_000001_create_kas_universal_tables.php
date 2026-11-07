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
        // 1. Organisasi (Tenant)
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // contoh: HIMTECH, BEM, HIMAIF
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('status')->default('active'); // active, inactive
            $table->timestamps();
        });

        // 2. Anggota Organisasi
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('member_number'); // NIM atau ID anggota
            $table->string('name');
            $table->string('phone')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['organization_id', 'member_number']);
        });

        // 3. Relasi User ke Organisasi & Peran
        Schema::create('organization_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('role'); // bendahara, staff, anggota
            $table->foreignId('member_id')->nullable()->constrained('members')->nullOnDelete();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['organization_id', 'user_id']);
        });

        // 4. Kategori Transaksi Kas
        Schema::create('cash_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('type'); // in, out
            $table->timestamps();
        });

        // 5. Transaksi Kas Masuk & Keluar
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->string('type'); // in, out
            $table->foreignId('category_id')->nullable()->constrained('cash_categories')->nullOnDelete();
            $table->decimal('amount', 15, 2);
            $table->date('transaction_date');
            $table->text('description');
            $table->string('proof_path')->nullable(); // bukti transfer atau nota fisik
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->boolean('is_locked')->default(true);
            $table->unsignedBigInteger('reversal_of')->nullable();
            $table->timestamps();
        });

        // 6. Periode Iuran / Kas
        Schema::create('dues_periods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->string('title'); // contoh: Iuran Kas November 2026
            $table->decimal('amount', 15, 2);
            $table->date('due_date'); // Jatuh tempo penentu tepat waktu vs terlambat
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // 7. Tagihan Iuran Anggota
        Schema::create('dues_bills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('dues_period_id')->constrained()->cascadeOnDelete();
            $table->foreignId('member_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 15, 2);
            $table->string('status')->default('unpaid'); // unpaid, pending, paid
            $table->boolean('is_on_time')->nullable(); // true: tepat waktu, false: terlambat
            $table->string('proof_path')->nullable();
            $table->dateTime('paid_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('payment_method')->default('cash'); // cash, transfer
            $table->timestamps();

            $table->unique(['dues_period_id', 'member_id']);
        });

        // 8. Log Aktivitas & Audit Trail
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('action'); // create_org, add_member, create_bill, pay_bill, add_expense
            $table->string('subject_type')->nullable();
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->json('payload')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('dues_bills');
        Schema::dropIfExists('dues_periods');
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('cash_categories');
        Schema::dropIfExists('organization_user');
        Schema::dropIfExists('members');
        Schema::dropIfExists('organizations');
    }
};

