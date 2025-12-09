<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
  protected $table = 'alumnos';

  protected $fillable = [
    'matricula',
    'first_name',
    'last_name',
    'email',
    'birth_date',
    'address',
    'phone',
    'status'
  ];

  public function courses()
  {
    return $this->belongsToMany(Course::class, 'student_course', 'student_id', 'course_id')
      ->withPivot('final_grade', 'status', 'enrolled_at');
  }
}
