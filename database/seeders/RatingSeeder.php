<?php

namespace Database\Seeders;

use App\Models\Rating;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RatingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
           
            Rating::create([
                'maximum' => "100",
                'minimum' => "90",
                'rating' => "Very High"

            ]);
          
            Rating::create([
                'maximum' => "90",
                'minimum' => "80",
                'Rating' => "Very High"
            ]);
           
            Rating::create([
                'maximum' => "80",
                'minimum' => "70",
                'Rating' => "Medium"
            ]);
          
            Rating::create([
                'maximum' => "70",
                'minimum' => "60",
                'Rating' => "Medium"
            ]);
         
              Rating::create([
                'maximum' => "60",
                'minimum' => "50",
                'Rating' => "Medium"
            ]);
           
            Rating::create([
                'maximum' => "50",
                'minimum' => "40",
                'Rating' => "Low"
            ]);
          
            Rating::create([
                'maximum' => "40",
                'minimum' => "30",
                'Rating' => "Low"
            ]);
          
            Rating::create([
                'maximum' => "30",
                'minimum' => "20",
                'Rating' => "Low"
            ]);
              
              Rating::create([
                'maximum' => "20",
                'minimum' => "10",
                'Rating' => "Very Low"
            ]);
         
            Rating::create([
                'maximum' => "10",
                'minimum' => "0",
                'Rating' => "very Low"
            ]);
         
    }
}
