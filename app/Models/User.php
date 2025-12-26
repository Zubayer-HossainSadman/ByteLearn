<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'picture',
        'learning_streak',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // For instructors - courses they teach
    public function courses(): HasMany
    {
        return $this->hasMany(Course::class, 'instructor_id');
    }

    // For students - courses they're enrolled in
    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class, 'user_id');
    }

    public function quizAttempts(): HasMany
    {
        return $this->hasMany(QuizAttempt::class, 'user_id');
    }

    public function discussions(): HasMany
    {
        return $this->hasMany(Discussion::class, 'user_id');
    }

    public function notes(): HasMany
    {
        return $this->hasMany(Note::class, 'user_id');
    }

    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class, 'user_id');
    }

    public function chatInteractions(): HasMany
    {
        return $this->hasMany(AIChatInteraction::class, 'user_id');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'user_id');
    }

    public function isInstructor(): bool
    {
        return $this->role === 'instructor';
    }

    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    public function getTotalEnrolledCourses()
    {
        return $this->enrollments()->count();
    }

    public function getTotalCompletedCourses()
    {
        return $this->certificates()->count();
    }

    public function getCompletionRate()
    {
        $total = $this->getTotalEnrolledCourses();
        if ($total == 0) return 0;

        $completed = $this->getTotalCompletedCourses();
        return round(($completed / $total) * 100, 2);
    }
}
