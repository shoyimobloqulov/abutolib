<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Test extends Model
{
    protected $fillable = [
        'type',
        'title',
        'description',
        'questions_count',
        'start_at',
        'duration_minutes',
        'end_at',
        'passing_score',
        'attempts_allowed',
        'show_answers',
        'shuffle_questions',
        'shuffle_answers',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'questions_count' => 'integer',
        'duration_minutes' => 'integer',
        'passing_score' => 'integer',
        'attempts_allowed' => 'integer',
        'show_answers' => 'boolean',
        'shuffle_questions' => 'boolean',
        'shuffle_answers' => 'boolean',
    ];

    // Quizzes (savollar) bilan M:N (pivot: test_quiz)
    public function quizzes(): BelongsToMany
    {
        return $this->belongsToMany(Quiz::class, 'test_quiz')
            ->withPivot(['order', 'points'])
            ->withTimestamps()
            ->orderBy('test_quiz.order');
    }

    // Agar urinishlarni alohida jadvalda saqlasangiz:
    public function attempts(): HasMany
    {
        return $this->hasMany(TestAttempt::class);
    }

    // Amaliy yordamchi: testning tugash vaqtini hisoblash (agar start_at va duration mavjud bo'lsa)
    public function getComputedEndAtAttribute()
    {
        if ($this->end_at) {
            return $this->end_at;
        }

        if ($this->start_at && $this->duration_minutes) {
            return $this->start_at->copy()->addMinutes($this->duration_minutes);
        }

        return null;
    }

    // Test hozir faolmi
    public function isActive(): bool
    {
        $now = Carbon::now();
        if ($this->start_at && $this->end_at) {
            return $now->between($this->start_at, $this->end_at);
        }

        if ($this->start_at && $this->duration_minutes) {
            return $now->between($this->start_at, $this->getComputedEndAtAttribute());
        }

        // agar start_at bo'lmasa, hisoblashga qarab true/false qaytarish mumkin
        return true;
    }
}
