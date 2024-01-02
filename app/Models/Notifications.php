<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifications extends Model
{
    use HasFactory;

    protected $table = "notifications";

    protected $fillable = [
        'user_id', 'all_notifications', 'takedown_requests', 'takedown_completed', 'news_and_updates', 'reminders'
    ];
}
