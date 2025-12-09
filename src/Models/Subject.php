<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $table = 'subjects';

    protected $fillable = [
        'code',
        'name',
        'description',
        'credits',
        'weekly_hours',
        'knowledge_area',
        'teacher_id',
        'status'
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'subject_course', 'subject_id', 'course_id');
    }
}
