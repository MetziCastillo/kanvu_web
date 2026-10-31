<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'student_id',
    'school_group_id',
    'start_date',
    'end_date',
])]

class Student_group_assignment extends Model
{
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function schoolGroup()
    {
        return $this->belongsTo(School_group::class, 'school_group_id');
    }
}
