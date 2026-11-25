<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class PsychologyAppointment extends Model
{

    use HasFactory;

    protected $table = 'psychology_appointments';

    protected $fillable = [
        'student_id',
        'employee_id',
        'area_id',
        'modality_id',
        'status_id',
        'appointment_date',
        'time_start',
        'time_end',
        'reason',
        'instructions',
        'notes',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }
    public function modality()
    {
        return $this->belongsTo(Modality::class, 'modality_id');
    }
    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }
    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id');
    }

    public function studentDetails()
    {
        return $this->hasOne(Student::class, 'user_id', 'student_id');
    }
}
