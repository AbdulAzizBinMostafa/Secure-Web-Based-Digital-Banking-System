<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1.4 Profile Management: Add MFA and Transaction Limits to Users
        Schema::table('users', function (Blueprint $table) {
            $table->string('otp_code')->nullable();
            $table->timestamp('otp_expires_at')->nullable();
            $table->boolean('is_mfa_verified')->default(false);
            $table->decimal('daily_transaction_limit', 15, 2)->default(50000.00);
        });

        // 1.2 Dashboard & Balance: Accounts Table
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('account_number', 20)->unique();
            $table->decimal('balance', 15, 2)->default(0.00);
            $table->enum('status', ['active', 'suspended'])->default('active');
            $table->timestamps();
        });

        // 1.3 Beneficiary Maintenance
        Schema::create('beneficiaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('account_number', 20);
            $table->string('bank_name');
            $table->timestamps();
        });

        // 1.4 Transaction History
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['credit', 'debit']);
            $table->decimal('amount', 15, 2);
            $table->string('description');
            $table->string('reference_no')->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('beneficiaries');
        Schema::dropIfExists('accounts');
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['otp_code', 'otp_expires_at', 'is_mfa_verified', 'daily_transaction_limit']);
        });
    }
};