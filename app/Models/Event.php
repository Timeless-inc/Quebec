<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'requisition_type_id',
        'title',
        'start_date',
        'end_date',
        'is_active',
        'created_by'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];
    
    public function isExpiringSoon()
    {
        return $this->end_date->isToday();
    }
    

    public function hasExpired()
    {
        return $this->end_date->isPast();
    }
    
    public function daysUntilExpiration()
    {
        if ($this->hasExpired()) {
            return 0;
        }
        
        return Carbon::now()->startOfDay()->diffInDays($this->end_date, false);
    }
    
    public function getStatusMessage()
    {
        if ($this->hasExpired()) {
            return 'Evento expirado';
        }
        
        if ($this->isExpiringSoon()) {
            return 'Evento próximo de encerramento';
        }
        
        return null;
    }
    
    public function scopeActiveAndCurrent($query)
    {
        return $query->where('is_active', true)
                     ->where('end_date', '>=', Carbon::today());
    }
}