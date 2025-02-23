<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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
        $query->whereDate('end_date', '>=', now()->subDay());
        $query->orWhereNull('end_date');
    });
    
    static::created(function ($event) {
        $deleteDate = Carbon::parse($event->end_date)->addDay();
        $event->delete_at = $deleteDate;
    });
}

}
