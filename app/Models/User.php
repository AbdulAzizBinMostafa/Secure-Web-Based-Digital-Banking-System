<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'otp_code', 'otp_expires_at', 
        'is_mfa_verified', 'daily_transaction_limit', 'role',
        'national_id', 'phone_number'
    ];

    protected $hidden = [
        'password', 'remember_token', 'otp_code', 'national_id'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'national_id' => 'encrypted', 
        'phone_number' => 'encrypted',
    ];
    
    // --- MODULE 1 RELATIONSHIPS ---
    
    public function account()
    {
        return $this->hasOne(Account::class);
    }

    public function beneficiaries()
    {
        return $this->hasMany(Beneficiary::class);
    }
}