<?php

namespace Database\Seeders;

use App\Models\Notifications;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Notifications\Notification;

class NotificationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Notifications::create([
            'user_id' => "1",
            'all_notifications' => "true",
            'takedown_requests' => "true",
            'takedown_completed' => "true",
            'news_and_updates' => "true",
            'reminders' => "true",

        ]);
    }
}
