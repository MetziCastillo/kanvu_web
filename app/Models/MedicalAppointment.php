<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MedicalAppointment extends Model
{
    use HasFactory;

    protected $table = 'medical_appointments';

    protected $fillable = [
        'student_id',
        'employee_id',
        'consultation_date',
        'time_start',
        'time_end',
        'reason',
        'instructions',
        'notes',
        'enable',
    ];

    protected $casts = [
        'enable' => 'boolean',
        'consultation_date' => 'date',
    ];


    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }
}
