<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizAttempt extends Model
{
    use HasFactory;

    const UPDATED_AT = null;

    protected $fillable = [
        'quiz_id',
        'user_id',
        'score',
        'attempt_date',
    ];

    protected $casts = [
        'attempt_date' => 'datetime',
        'created_at' => 'datetime',
    ];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getScorePercentage()
    {
        $totalQuestions = 10; // Default or retrieve from quiz
        if ($totalQuestions == 0) return 0;
        return round(($this->score / $totalQuestions) * 100, 2);
    }

    public function isPassed()
    {
        return $this->getScorePercentage() >= 60;
    }
}
