<?php

namespace Database\Seeders;

use App\Models\PaymentPlan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Notifications\Notification;

class PaymentPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PaymentPlan::create([
            'plan' => "Free",
        ]);
        PaymentPlan::create([
            'plan' => "Basic",
        ]);
        PaymentPlan::create([
            'plan' => "Premium",
        ]);
    }
}
