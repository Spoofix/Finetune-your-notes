<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSwitch extends Model
{
    use HasFactory;

    protected $table = "user_switches";

    protected $fillable = [
        'admin_id', 'user_id', 'switched_at', 'updated_at', 'admin_ip','status'
    ];
}
