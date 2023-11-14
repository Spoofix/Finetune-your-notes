<?php

namespace Database\Seeders;

use App\Models\Weight;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WeightSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Weight::create([
            'factors' => "Ui rating",
            'weights' => "10",
        ]);
        Weight::create([
            'factors' => "Color Scheme",
            'weights' => "5",
        ]);
        Weight::create([
            'factors' => "Content",
            'weights' => "5",
        ]);
        Weight::create([
            'factors' => "Domain ",
            'weights' => "20",
        ]);
        Weight::create([
            'factors' => "HTML",
            'weights' => "17.5",
        ]);
        Weight::create([
            'factors' => "Age",
            'weights' => "10",
        ]);
        Weight::create([
            'factors' => "reputation",
            'weights' => "17.5",
        ]);
    }
}
