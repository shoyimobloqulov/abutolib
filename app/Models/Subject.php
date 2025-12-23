<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = [
        'name',
        'image',
        'description',
    ];

    public function topics()
    {
        return $this->hasMany(Topic::class);
    }
}
