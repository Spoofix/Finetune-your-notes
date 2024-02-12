<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportformTakedownDetails extends Model
{
    use HasFactory;

    protected $table = "reportform_takedown_details";

    protected $fillable = [
        'abuse_type', 'evidence_urls', 'additional_notes', 'observed_date', 'attachments', 'carbon_copy_request_to', 'reported_by_user_id', 'reported_via'
    ];
}
