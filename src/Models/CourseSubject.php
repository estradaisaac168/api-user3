<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseSubject extends Model
{
    protected $table = 'course_subject';

    public $timestamps = false;

    protected $fillable = [
        'curso_id',
        'materia_id',
        'schedule',
        'classroom'
    ];
}
