<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Career;
use App\Models\Department;
use App\Models\School_group;
use App\Models\MedicalAppointment;
use App\Models\Employee;
use App\Models\Student;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MedicalManagementController extends Controller
{
    public function index()
    {
        $roles = Role::query()->select('id', 'name')->get();
        $careers = Career::query()->select('id', 'name')->where('enable', true)->get();
        $department = Department::query()->select('id', 'name')->get();

        return Inertia::render('appointments/Medical', [
            'roles' => $roles,
            'careers' => $careers,
            'department' => $department,
        ]);
    }

    public function groupsByCareer(Career $career)
    {
        $groups = School_group::query()
            ->join('groups', 'groups.id', '=', 'school_groups.group_id')
            ->join('grades', 'grades.id', '=', 'school_groups.grade_id')
            ->join('school_years', 'school_years.id', '=', 'school_groups.school_year_id')
            ->where('school_groups.career_id', $career->id)
            ->where('school_groups.enable', true)
            ->select(
                'school_groups.id',
                'groups.name as group_name',
                'grades.name as grade_name',
                'school_years.name as school_year_name'
            )
            ->orderBy('groups.name')
            ->get();

        return response()->json($groups);
    }

    public function searchStudent(string $matricula)
    {
        $student = DB::table('users as us')
            ->join('students as sd', 'sd.user_id', '=', 'us.id')
            ->leftJoin('student_group_assignments as sga', function ($j) {
                $j->on('sga.student_id', '=', 'sd.id')
                    ->whereRaw('sga.id = (select max(id) from student_group_assignments where student_id = sd.id)');
            })
            ->leftJoin('school_groups as sg', 'sg.id', '=', 'sga.school_group_id')
            ->leftJoin('careers as c', 'c.id', '=', 'sg.career_id')
            ->leftJoin('groups as g', 'g.id', '=', 'sg.group_id')
            ->leftJoin('grades as gd', 'gd.id', '=', 'sg.grade_id')
            ->where('sd.matricula', $matricula)
            ->select(
                'us.first_name as name',
                'us.last_name',
                'us.id as student_id',
                'sd.matricula',
                'c.id as career_id',
                'sg.id as group_id',
                'gd.name as grade_name',
                'g.name as group_name',
            )
            ->first();

        if (!$student) {
            return response()->json(['message' => 'No encontrado'], 404);
        }
        return response()->json($student);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'manual'            => 'boolean',
            'student_id'        => 'required|integer|exists:users,id',
            'career_id'         => 'required|integer|exists:careers,id',
            'group_id'          => 'required|integer|exists:school_groups,id',
            'appointment_date'  => 'required|date',
            'start_time'        => 'required',
            'end_time'          => 'required',
            'discomfort'        => 'required|string',
            'indications'       => 'nullable|string',
            'observations'      => 'nullable|string',
        ]);


        $employeeId = Auth::id();

        $studentId = $data['student_id'] ?? null;

        if ($request->manual && !$studentId) {
            return response()->json(['message' => 'El registro manual requiere crear un usuario primero.'], 422);
            // (Aquí podrías implementar la creación del User + Student si lo deseas)
        }

        $appointment = MedicalAppointment::create([
            'student_id'        => $studentId,
            'employee_id'       => $employeeId,
            'consultation_date' => $data['appointment_date'],
            'time_start'        => $data['start_time'],
            'time_end'          => $data['end_time'],
            'reason'            => $data['discomfort'],
            'instructions'      => $data['indications'] ?? null,
            'notes'             => $data['observations'] ?? null,
            'enable'            => true,
        ]);

        return response()->json(['appointment' => $appointment], 201);
    }

    public function detailsAppointment(MedicalAppointment $appointment)
    {
        $medicalAppointmentsDetails = DB::table('medical_appointments AS ma')
            ->leftJoin('users AS u', 'u.id', '=', 'ma.student_id')
            ->leftJoin('students AS s', 's.user_id', '=', 'u.id')
            ->leftJoin('student_group_assignments AS sga', 'sga.student_id', '=', 's.id')
            ->leftJoin('school_groups AS sc', 'sc.id', '=', 'sga.school_group_id')
            ->leftJoin('grades AS gd', 'gd.id', '=', 'sc.grade_id')
            ->leftJoin('groups AS gs', 'gs.id', '=', 'sc.group_id')
            ->leftJoin('careers AS cr', 'cr.id', '=', 'sc.career_id')
            ->where('ma.enable', 1)
            ->where('ma.id', $appointment->id)
            ->select(
                'ma.id',
                'u.first_name',
                'u.last_name',
                's.matricula',
                'cr.name AS career_name',
                'gd.name AS grade_name',
                'gs.name AS group_name',
                'ma.consultation_date',
                DB::raw("DATE_FORMAT(ma.time_start, '%H:%i') AS time_start"),
                DB::raw("DATE_FORMAT(ma.time_end, '%H:%i') AS time_end"),
                'ma.reason',
                'ma.notes',
                'ma.instructions'
            )
            ->orderBy('ma.id', 'DESC')
            ->first();


        return Inertia::render('appointments/Details', [
            'detailsAppointment' => $medicalAppointmentsDetails
        ]);
    }
}
