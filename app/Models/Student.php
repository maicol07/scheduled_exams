<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Student extends Model
{
    protected $fillable = ['name'];

    public function classrooms(): BelongsToMany
    {
        return $this->belongsToMany(Classroom::class, 'classrooms_students');
    }
}
