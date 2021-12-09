<?php

namespace App\Http\Controllers\Api;

use App\Models\Classroom;
use Illuminate\Database\Eloquent\Model;

class ClassroomController extends ApiController
{
    protected string|Model $model = Classroom::class;
}
