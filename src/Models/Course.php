<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $table = 'courses';

    protected $fillable = [
        'code',
        'name',
        'description',
        'level',
        'start_date',
        'end_date',
        'max_capacity',
        'status'
    ];

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'course_subject', 'course_id', 'subject_id')
                    ->withPivot('schedule', 'classroom');
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_course', 'course_id', 'student_id')
                    ->withPivot('status', 'final_grade');
    }
}
