<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentCourse extends Model
{
    protected $table = 'student_course';

    public $timestamps = false;

    protected $fillable = [
        'student_id',
        'course_id',
        'final_grade',
        'status',
        'enrolled_at'
    ];
}
