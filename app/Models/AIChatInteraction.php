<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AIChatInteraction extends Model
{
    use HasFactory;

    const UPDATED_AT = null;

    protected $table = 'ai_chat_interactions';

    protected $fillable = [
        'user_id',
        'lesson_id',
        'question',
        'answer',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }
}
