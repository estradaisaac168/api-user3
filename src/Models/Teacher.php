<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $table = 'teachers';

    protected $fillable = [
        'employee_number',
        'first_name',
        'last_name',
        'email',
        'specialty',
        'hired_date',
        'phone',
        'status'
    ];

    public function subjects()
    {
        return $this->hasMany(Subject::class, 'teacher_id');
    }
}
