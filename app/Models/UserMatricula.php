<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserMatricula extends Model
{
    protected $fillable = [
        'user_id',
        'matricula',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
