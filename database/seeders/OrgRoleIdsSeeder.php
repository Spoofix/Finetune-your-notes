<?php

namespace Database\Seeders;

use App\Models\OrgRoleId;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Notifications\Notification;

class OrgRoleIdsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        OrgRoleId::create([
            'org_role' => "OrgAdmin",
        ]);
        OrgRoleId::create([
            'org_role' => "OrgUser",
        ]);
    }
}
