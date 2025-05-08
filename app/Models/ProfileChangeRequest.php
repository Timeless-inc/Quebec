<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfileChangeRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'field',
        'current_value',
        'new_value',
        'document_path',
        'status',
        'admin_comment',
    ];


    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_NEEDS_REVIEW = 'needs_review';


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}