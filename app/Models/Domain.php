<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Domain extends Model
{
    use HasFactory;

    protected $table = "domains";

    protected $fillable = [
        'id', 'user_id', 'domain_name', 'status', 'org_id'
    ];

    protected $appends = ['formated_updated_at', 'is_queued'];

    public function getFormatedUpdatedAtAttribute($key)
    {
        return $this->created_at->format("Y-m-d H:i");
    }

    public function getIsQueuedAttribute()
    {
        try {
            return SpoofedDomain::processingDomains()->where('domain_id', $this->id)->exists();
        } catch (\Exception $exception) {
            Log::error("Error > " . $exception->getMessage() . ' <> ' . $exception->getTraceAsString());
        }

        return false;
    }
}
