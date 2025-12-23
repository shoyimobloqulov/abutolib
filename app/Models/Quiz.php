<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $fillable = ['topic_id', 'question', 'type', 'image'];

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function tests()
    {
        return $this->belongsToMany(Test::class, 'test_quiz')->withPivot(['order','points'])->withTimestamps();
    }

    public function quizzes()
    {
        return $this->belongsToMany(Quiz::class, 'test_quiz')
            ->withPivot(['order','points'])
            ->withTimestamps();
    }
}
