<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CountriesCybersecurity extends Model
{
    use HasFactory;
    protected $table = "countries_cybersecurity_reachout_info";

    protected $fillable = [
        'email', "country", "organization", "url"
    ];
}
