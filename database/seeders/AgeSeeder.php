<?php

namespace Database\Seeders;

use App\Models\Age;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        Age::create([
            'age_in_days' => "90",
            'rating' => "High",
        ]);
    
        Age::create([
            'age_in_days' => "180",
            'rating' => "Medium",
        ]);
        
        Age::create([
            'age_in_days' => "365",
            'rating' => "Low",
        ]);
        
        Age::create([
            'age_in_days' => "366",
            'rating' => "Very Low",
        ]);
    }
}
