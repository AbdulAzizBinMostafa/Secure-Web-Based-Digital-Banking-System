<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Beneficiary extends Model
{
    protected $fillable = ['user_id', 'name', 'account_number', 'bank_name'];
}