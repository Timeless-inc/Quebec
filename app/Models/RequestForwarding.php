<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestForwarding extends Model
{
    use HasFactory;

    protected $fillable = [
        'requerimento_id',
        'sender_id',
        'receiver_id',
        'status',
        'internal_message',
        'is_returned'
    ];

    public function requerimento()
    {
        return $this->belongsTo(ApplicationRequest::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}