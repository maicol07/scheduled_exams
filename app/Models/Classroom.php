<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Collection;

class Classroom extends Model
{
    protected $fillable = ['name', 'description', 'capacity', 'status'];

    public function allStudents(): Collection
    {
        return $this->students->merge($this->users);
    }

    public function students(): HasManyThrough
    {
        return $this->hasManyThrough(Student::class, ClassroomsStudent::class);
    }

    public function admins(): HasMany
    {
        return $this->users()->where('classrooms_students.is_admin', true);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class, ClassroomsStudent::class);
    }
}
