<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'label',
        'can_receive_forwardings',
    ];

    protected $casts = [
        'can_receive_forwardings' => 'boolean',
    ];

    public static function getAllForwardableRoleLabels(): array
    {
        $dynamic = static::where('can_receive_forwardings', true)->pluck('label')->toArray();
        return array_merge(['Diretor Geral'], $dynamic);
    }

    public static function getForwardableRoles()
    {
        return static::where('can_receive_forwardings', true)->get();
    }
}
