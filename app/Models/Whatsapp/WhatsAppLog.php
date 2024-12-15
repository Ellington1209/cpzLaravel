<?php

namespace App\Models\Whatsapp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsAppLog extends Model
{
    use HasFactory;
    protected $table = 'whatsapp_logs';
    protected $fillable = [
        'user_name',
        'phone_number',
        'message',
        'status',
        'details',
    ];
}
