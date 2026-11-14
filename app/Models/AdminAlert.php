<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class AdminAlert extends Model {
    protected $fillable = ['user_id', 'type', 'message', 'is_resolved'];
}