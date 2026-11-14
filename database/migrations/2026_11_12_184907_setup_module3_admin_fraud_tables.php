<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // MD NAIMUL HAQUE: Panic PIN on Users Table
        Schema::table('users', function (Blueprint $table) {
            $table->string('panic_pin')->nullable();
        });

        // ABDUL AZIZ: Fraud Flags on Transactions
        Schema::table('transactions', function (Blueprint $table) {
            $table->boolean('is_flagged')->default(false);
            $table->string('fraud_reason')->nullable();
        });

        // SUPER ADMIN: System Alerts Table (Listens for Fraud and Duress)
        Schema::create('admin_alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('type'); // e.g., 'fraud_flag', 'duress_login'
            $table->text('message');
            $table->boolean('is_resolved')->default(false);
            $table->timestamps();
        });

        // MD NAIMUL HAQUE: TTL Payment Links Table
        Schema::create('payment_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('token')->unique();
            $table->decimal('amount', 15, 2);
            $table->timestamp('expires_at');
            $table->boolean('is_used')->default(false);
            $table->timestamps();
        });

        // MAHIM TALUKDER APURBO: Disposable Virtual Accounts Table
        Schema::create('virtual_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('virtual_number')->unique();
            $table->boolean('is_used')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('virtual_accounts');
        Schema::dropIfExists('payment_links');
        Schema::dropIfExists('admin_alerts');
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['is_flagged', 'fraud_reason']);
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('panic_pin');
        });
    }
};