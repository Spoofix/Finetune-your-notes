<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;

class Domain extends Model
{
    use HasFactory;

    protected $table="domains";

    protected $fillable = [
        'user_id', 'domain_name', 'status'
    ];

   protected $appends =['formated_updated_at','is_queued'];

   public function getFormatedUpdatedAtAttribute($key)
   {
      return $this->created_at->format("Y-m-d H:i");
   }

    public function getIsQueuedAttribute()
    {
        try {
            $queueName = "ScanDomains" . $this->id;
            return DB::table("jobs")->where('tag',$queueName)->exists();
        }catch (\Exception $exception){
            Log::error("Error > ".$exception->getMessage().' <> '.$exception->getTraceAsString());
        }

        return false;
    }

}
