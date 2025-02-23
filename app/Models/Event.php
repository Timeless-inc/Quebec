<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'title',
        'start_date',
        'end_date',
        'created_by'
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::addGlobalScope('active', function ($query) {
            $query->where('end_date', '>=', now());
        });
    }
}
