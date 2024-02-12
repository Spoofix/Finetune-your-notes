<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompletedAndInprogress extends Model
{
    use HasFactory;

    protected $table = "completed_and_inprogress";

    protected $fillable = [
        'domain_id', 'reporting_user_id', 'authorization_date', 'start_date', 'end_date', 'time_elapsed', 'status'
    ];
}
