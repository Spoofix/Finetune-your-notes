<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => "Olang Daniel",
            'organization' => "Olang",
            'phone_number' => "Daniel",
            'email' => "admin@gmail.com",
            'password' => Hash::make('1qa2ws3ed4rf'),
            'role_id' => 1,
            'status' => "ACTIVE",
        ]);
    }
}
