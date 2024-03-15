<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportedSpoofDomains extends Model
{
    use HasFactory;
    protected $table = "reported_spoof_domains";

    protected $fillable = [
        'spoof_id', 'reportingName', 'admin_id', 'reported_via'
    ];
}
