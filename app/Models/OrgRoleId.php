<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrgRoleId extends Model
{
    use HasFactory;

    protected $table = "org_role_ids";

    protected $fillable = [
        'org_role'
    ];
}
