<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    use HasFactory;

    protected $table="domains";

    protected $fillable = [
        'user_id', 'domain_name', 'status'
    ];

   protected $appends =['formated_updated_at'];

   public function getFormatedUpdatedAtAttribute($key)
   {
      return $this->created_at->format("Y-m-d H:i");
   }
}
