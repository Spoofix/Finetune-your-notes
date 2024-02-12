<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Weight extends Model
{
    use HasFactory;

    protected $table="ratings_weight_to_overall";

    protected $fillable = [
        'factors', 'weights'
    ];
}
