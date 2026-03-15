<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reminder extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'dosen_id',
        'phone_override',
        'title',
        'message',
        'scheduled_at',
        'status',
        'is_whatsapp',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'is_whatsapp' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class);
    }
}
