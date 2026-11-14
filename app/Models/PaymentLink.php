<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class PaymentLink extends Model {
    protected $fillable = ['user_id', 'token', 'amount', 'expires_at', 'is_used'];
}