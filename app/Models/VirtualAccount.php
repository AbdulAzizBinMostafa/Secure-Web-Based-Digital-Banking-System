<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class VirtualAccount extends Model {
    protected $fillable = ['user_id', 'virtual_number', 'is_used'];
}