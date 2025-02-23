<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

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
            $query->whereDate('end_date', '>=', now()->subDay())
                  ->orWhereNull('end_date');
        });

        static::created(function ($event) {
            if ($event->end_date) {
                $event->delete_at = Carbon::parse($event->end_date)->addDay();
                $event->save();
            }
        });

        static::booted(function () {
            static::whereDate('end_date', '<', now()->subDay())->delete();
        });
    }
}
