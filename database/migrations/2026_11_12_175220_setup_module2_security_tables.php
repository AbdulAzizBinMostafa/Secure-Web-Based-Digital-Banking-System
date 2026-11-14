<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Adding RBAC and PII fields to Users table
        Schema::table('users', function (Blueprint $table) {
            // Abdul Aziz: Role-Based Access Control
            $table->enum('role', ['customer', 'admin', 'compliance'])->default('customer');
            
            // Md. Naimul Haque: Sensitive PII Data (Will be encrypted in DB)
            $table->text('national_id')->nullable(); 
            $table->text('phone_number')->nullable();
        });

        // Mahim: Login Activity Monitoring Table
        Schema::create('login_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('ip_address');
            $table->string('user_agent'); // Device Info
            $table->string('device_type')->nullable(); // Mobile, Desktop, etc.
            $table->timestamp('login_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('login_histories');
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'national_id', 'phone_number']);
        });
    }
};