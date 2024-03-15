<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reporting extends Model
{
    use HasFactory;
    protected $table = "reporting";

    protected $fillable = [
        "name", "email", "form_url", "Can_Report"
    ];
}
