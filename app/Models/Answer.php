<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $fillable = ['quiz_id', 'answer', 'is_correct'];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }
}
