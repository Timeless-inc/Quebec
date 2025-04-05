<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequisitionTypeEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'requisition_type_id',
        'requires_event',
    ];

    protected $casts = [
        'requires_event' => 'boolean',
    ];
}