<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'enrollment_date',
        'progress',
    ];

    protected $casts = [
        'enrollment_date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function updateProgress()
    {
        $totalLessons = $this->course->lessons()->count();
        if ($totalLessons == 0) {
            $this->progress = 0;
            return;
        }

        $completedLessons = $this->course->lessons()
                                         ->join('quiz_attempts', 'lessons.id', '=', 'quiz_attempts.quiz_id')
                                         ->where('quiz_attempts.user_id', $this->user_id)
                                         ->distinct('lessons.id')
                                         ->count();

        $this->progress = round(($completedLessons / $totalLessons) * 100, 2);
    }

    public function isCompleted()
    {
        return $this->progress >= 100;
    }
}
