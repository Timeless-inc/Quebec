<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'title', 'message', 'is_read'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //verificar uso dessa função
    public static function markAllAsRead($userId)
    {
    return self::where('user_id', $userId)
        ->where('is_read', false)
        ->update(['is_read' => true]);
    }
}