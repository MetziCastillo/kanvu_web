<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'user_id',
    'matricula',
    'birthday',
    'number_phone',
    'number_phone_emergency',
])]


class Student extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function groupAssignment()
    {
        return $this->hasOne(Student_group_assignment::class, 'student_id');
    }
}
