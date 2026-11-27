<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Career;
use App\Models\Department;
use App\Models\School_group;
use App\Models\MedicalAppointment;
use App\Models\Employee;
use App\Models\Modality;
use App\Models\PsychologyAppointment;
use App\Models\Student;
use Inertia\Inertia;
use App\Support\Roles;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class MedicalManagementController extends Controller
{
    public function index()
    {
        $roles = Role::query()->select('id', 'name')->get();
        $careers = Career::query()->select('id', 'name')->where('enable', true)->get();
        $department = Department::query()->select('id', 'name')->get();
        $area = Area::query()->select('id', 'name')->where('enable', true)->get();
        $modality = Modality::query()->select('id', 'name')->where('enable', true)->get();

        return Inertia::render('appointments/Medical', [
            'roles' => $roles,
            'careers' => $careers,
            'department' => $department,
            'area' => $area,
            'modality' => $modality,
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
        $user = Auth::user();

        $roleId = (int) $user->id_rol;

        $data = $request->validate([
            'manual'           => 'boolean',
            'student_id'       => 'required|integer|exists:users,id',
            'career_id'        => 'required|integer|exists:careers,id',
            'group_id'         => 'required|integer|exists:school_groups,id',
            'appointment_date' => 'required|date',
            'start_time'       => ['required', 'date_format:H:i'],
            'end_time'         => ['required', 'date_format:H:i', 'after:start_time'],
            'discomfort'       => 'required|string',
            'indications'      => 'nullable|string',
            'observations'     => 'nullable|string',
        ], [
            'end_time.after'         => 'La hora de término debe ser posterior a la hora de inicio.',
            'end_time.date_format'   => 'La hora de término no tiene un formato válido (HH:MM).',
            'start_time.date_format' => 'La hora de inicio no tiene un formato válido (HH:MM).',
        ]);

        $selectedDate = Carbon::parse($data['appointment_date'])->startOfDay();
        $today = Carbon::today();

        if ($selectedDate->lt($today)) {
            return response()->json([
                'errors' => [
                    'appointment_date' => ['La fecha de la cita no puede ser anterior a hoy.'],
                ],
            ], 422);
        }

        if (!in_array($roleId, [Roles::NURSE, Roles::PSYCHOLOGIST], true)) {
            return response()->json([
                'message' => 'No tienes permiso para crear citas.',
            ], 403);
        }

        $employeeId = $user->id;
        $studentId  = $data['student_id'];

        if ($request->boolean('manual') && !$studentId) {
            return response()->json(
                ['message' => 'El registro manual requiere crear un usuario primero.'],
                422
            );
        }

        if ($roleId === Roles::PSYCHOLOGIST) {
            $request->validate([
                'area_id'     => 'required|integer|exists:areas,id',
                'modality_id' => 'required|integer|exists:modalities,id',
            ], [
                'area_id.required'     => 'Debes seleccionar un área.',
                'modality_id.required' => 'Debes seleccionar una modalidad.',
            ]);
        }

        if ($this->slotIsTaken($roleId, $employeeId, $data['appointment_date'], $data['start_time'], $data['end_time'])) {
            return response()->json([
                'errors' => [
                    'start_time' => ['Ya tienes una cita en ese horario.'],
                ],
            ], 422);
        }

        if ($roleId === Roles::NURSE) {
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
        } else {
            $appointment = PsychologyAppointment::create([
                'student_id'       => $studentId,
                'employee_id'      => $employeeId,
                'area_id'          => (int) $request->input('area_id'),
                'modality_id'      => (int) $request->input('modality_id'),
                'status_id'        => 1,
                'appointment_date' => $data['appointment_date'],
                'time_start'       => $data['start_time'],
                'time_end'         => $data['end_time'],
                'reason'           => $data['discomfort'],
                'instructions'     => $data['indications'] ?? null,
                'notes'            => $data['observations'] ?? null,
            ]);
        }

        return response()->json(['appointment' => $appointment], 201);
    }

    private function slotIsTaken(int $roleId, int $employeeId, string $date, string $start, string $end): bool
    {
        $model = $roleId === Roles::NURSE
            ? MedicalAppointment::class
            : PsychologyAppointment::class;

        $dateColumn = $roleId === Roles::NURSE ? 'consultation_date' : 'appointment_date';

        return $model::query()
            ->where('employee_id', $employeeId)
            ->where($dateColumn, $date)
            ->where(function ($q) use ($start, $end) {
                $q->where('time_start', '<', $end)
                    ->where('time_end', '>', $start);
            })
            ->exists();
    }

    public function detailsAppointment($type, $appointmentId)
    {
        if (!in_array($type, ['medical', 'psychology'])) {
            abort(404);
        }

        $table = $type === 'medical' ? 'medical_appointments' : 'psychology_appointments';
        $dateField = $type === 'medical' ? 'consultation_date' : 'appointment_date';

        $details = DB::table($table . ' AS ma')
            ->leftJoin('users AS u', 'u.id', '=', 'ma.student_id')
            ->leftJoin('students AS s', 's.user_id', '=', 'u.id')
            ->leftJoin('student_group_assignments AS sga', 'sga.student_id', '=', 's.id')
            ->leftJoin('school_groups AS sc', 'sc.id', '=', 'sga.school_group_id')
            ->leftJoin('grades AS gd', 'gd.id', '=', 'sc.grade_id')
            ->leftJoin('groups AS gs', 'gs.id', '=', 'sc.group_id')
            ->leftJoin('careers AS cr', 'cr.id', '=', 'sc.career_id')
            ->where('ma.enable', 1)
            ->where('ma.id', $appointmentId)
            ->select(
                'ma.id',
                'u.first_name',
                'u.last_name',
                's.matricula',
                'cr.name AS career_name',
                'gd.name AS grade_name',
                'gs.name AS group_name',
                DB::raw("DATE_FORMAT(ma.{$dateField}, '%Y-%m-%d') AS consultation_date"),
                DB::raw("DATE_FORMAT(ma.time_start, '%H:%i') AS time_start"),
                DB::raw("DATE_FORMAT(ma.time_end, '%H:%i') AS time_end"),
                'ma.reason',
                'ma.notes',
                'ma.instructions'
            )
            ->first();

        if (!$details) {
            abort(404);
        }

        if ($type === 'psychology') {
            $extra = DB::table('psychology_appointments AS pa')
                ->leftJoin('modalities AS m', 'm.id', '=', 'pa.modality_id')
                ->leftJoin('areas AS a', 'a.id', '=', 'pa.area_id')
                ->leftJoin('statuses AS st', 'st.id', '=', 'pa.status_id')
                ->where('pa.id', $appointmentId)
                ->select('m.name AS modality_name', 'a.name AS area_name', 'pa.status_id', 'st.name AS status_name')
                ->first();

            $details = (object) array_merge((array) $details, (array) $extra);
        }

        return Inertia::render('appointments/Details', [
            'detailsAppointment' => $details,
            'type' => $type
        ]);
    }
}
