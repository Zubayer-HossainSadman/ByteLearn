<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'title',
        'content',
        'content_type',
        'sequence_number',
        'video_url',
        'pdf_url',
        'external_link',
        'external_link_label',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function quizzes(): HasMany
    {
        return $this->hasMany(Quiz::class);
    }

    public function discussions(): HasMany
    {
        return $this->hasMany(Discussion::class);
    }

    public function notes(): HasMany
    {
        return $this->hasMany(Note::class);
    }

    public function chatInteractions(): HasMany
    {
        return $this->hasMany(AIChatInteraction::class);
    }

    public function getCompletionRate()
    {
        $totalStudents = $this->course->getStudentCount();
        if ($totalStudents == 0) return 0;

        // Count students who have attempted quizzes for this lesson
        $completedCount = $this->quizzes()
                               ->join('quiz_attempts', 'quizzes.id', '=', 'quiz_attempts.quiz_id')
                               ->distinct('quiz_attempts.user_id')
                               ->count();

        return round(($completedCount / $totalStudents) * 100, 2);
    }
}
