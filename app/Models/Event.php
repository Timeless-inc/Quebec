<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class Event extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'requisition_type_id',
        'title',
        'start_date',
        'end_date',
        'is_active',
        'created_by',
        'is_exception',
        'exception_user_id'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
        'is_exception' => 'boolean'
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('active', function ($query) {
            $query->where('is_active', true)
                  ->whereDate('end_date', '>=', Carbon::today());
        });
    }

    public function isExpired()
    {
        return $this->end_date->endOfDay()->lt(Carbon::now());
    }
    
    public function isEndingToday()
    {
        return Carbon::now()->isSameDay($this->end_date);
    }
    
    public function isExpiringSoon($days = 3)
    {
        if ($this->isEndingToday()) {
            return true;
        }
        
        $daysLeft = $this->daysUntilEnd();
        return $daysLeft > 0 && $daysLeft <= $days;
    }

    public function daysUntilEnd()
    {
        if ($this->isExpired()) {
            return 0;
        }
        
        return Carbon::now()->startOfDay()->diffInDays($this->end_date->startOfDay());
    }
    
    public function daysUntilExpiration()
    {
        return $this->daysUntilEnd();
    }
    
    public function getExpirationMessage()
    {
        if ($this->isEndingToday()) {
            return "Evento próximo de encerramento";
        }
        
        $daysLeft = $this->daysUntilEnd();
        
        if ($daysLeft == 1) {
            return "Expira em 1 dia";
        }
        
        if ($daysLeft > 1 && $daysLeft <= 3) {
            return "Expira em {$daysLeft} dias";
        }
        
        return null;
    }

    public static function removeExpiredEvents()
    {
        $expiredEvents = self::withoutGlobalScopes()
            ->whereDate('end_date', '<', Carbon::today())
            ->whereNull('deleted_at')
            ->get();
        
        Log::info('Buscando eventos expirados para remover', [
            'count' => $expiredEvents->count(),
            'today' => Carbon::today()->format('Y-m-d'),
            'ids' => $expiredEvents->pluck('id')->toArray()
        ]);
        
        $deletedCount = 0;
        
        foreach ($expiredEvents as $event) {
            try {
                $event->delete();
                $deletedCount++;
                
                Log::info('Evento expirado excluído', [
                    'id' => $event->id,
                    'title' => $event->title,
                    'end_date' => $event->end_date->format('Y-m-d')
                ]);
            } catch (\Exception $e) {
                Log::error('Erro ao excluir evento expirado', [
                    'id' => $event->id,
                    'message' => $e->getMessage()
                ]);
            }
        }
        
        return $deletedCount;
    }

    public static function withExpired()
    {
        return self::withoutGlobalScope('active');
    }

    public static function onlyExpired()
    {
        return self::withoutGlobalScope('active')
            ->whereDate('end_date', '<', Carbon::today());
    }

    public function scopeActiveForUser($query, $userId, $isCradt = false)
    {
        return $query->where('is_active', true)
            ->whereDate('end_date', '>=', now())
            ->where(function($query) use ($userId, $isCradt) {
                $query->where('is_exception', false);
                
                if (!$isCradt) {
                    $query->orWhere(function($q) use ($userId) {
                        $q->where('is_exception', true)
                          ->where('exception_user_id', $userId);
                    });
                } else {
                    $query->orWhere('is_exception', true);
                }
            });
    }

    public function exceptionUser()
    {
        return $this->belongsTo(User::class, 'exception_user_id');
    }
}