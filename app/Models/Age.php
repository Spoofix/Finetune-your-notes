<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Age extends Model
{
    use HasFactory;

    protected $table="age_ratings";

    protected $fillable = [
       'age_in_days', 'rating'
    ];
}
