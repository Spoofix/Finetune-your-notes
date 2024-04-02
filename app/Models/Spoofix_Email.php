<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Spoofix_Email extends Model
{
    use HasFactory;
    protected $fillable = ['index', 'in_reply_to', 'references', 'from_address', 'subject', 'date', 'message_id', 'body'];
}
