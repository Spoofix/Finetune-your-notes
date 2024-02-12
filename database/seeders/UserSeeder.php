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
            'name' => "Ernest",
            'organization' => "Ernest",
            'phone_number' => "0233994949",
            'email' => "admin2@gmail.com",
            'password' => Hash::make('1qa2ws3ed4rf'),
            'role_id' => 1,
            'status' => "ACTIVE",
            'org_id' => 1,
            'org_role_id' => 1,
            'second_name' => "kiromo",
            'profile' => ''
        ]);
    }
}
