<?php

namespace App\Http\Controllers;

use App\Models\MedicalAppointment;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Support\Roles;

class DashboardDataController extends Controller
{
    public function informationUsers()
    {
        abort_unless((int) Auth::user()->role?->id === 1, 403);
        $usersInformation = User::query()
            ->select('id', 'status_id', 'id_rol')
            ->get();


        return Inertia::render(
            'dashboard/admin_role/Dashboard',
            [
                'usersInformation' => $usersInformation
            ]
        );
    }

    public function informationBoss()
    {
        $roleId = (int) Auth::user()->id_rol;
        abort_unless(Roles::isBoss($roleId), 403);

        $visibleRoles = Roles::visibleRolesFor($roleId);

        // "Hoy" y "semana" calculados desde Laravel (evita el bug de zona horaria de CURDATE)
        $today  = now()->toDateString();
        $monday = now()->startOfWeek()->toDateString();          // lunes
        $friday = now()->startOfWeek()->addDays(4)->toDateString(); // viernes

        $employees = DB::table('employees as e')
            ->join('users as u', 'u.id', '=', 'e.user_id')
            ->leftJoin('users_status as us', 'us.id', '=', 'u.status_id')
            ->leftJoin('medical_appointments as ma', 'ma.employee_id', '=', 'e.user_id')
            ->whereIn('u.id_rol', $visibleRoles)
            ->groupBy('e.id', 'e.user_id', 'u.first_name', 'u.last_name', 'u.id_rol', 'u.status_id', 'us.name')
            ->select([
                'e.id as employee_id',
                'e.user_id',
                'u.first_name',
                'u.last_name',
                'u.id_rol',
                'u.status_id',
                'us.name as status_name',
                DB::raw('COUNT(ma.id) as total_appointments'),
                DB::raw('SUM(CASE WHEN ma.consultation_date = ? THEN 1 ELSE 0 END) as appointments_today'),
                DB::raw('SUM(CASE WHEN ma.consultation_date BETWEEN ? AND ? THEN 1 ELSE 0 END) as appointments_this_week'),
            ])
            ->addBinding([$today, $monday, $friday], 'select')
            ->get();

        $schedules = DB::table('employee_schedules')
            ->whereIn('employee_id', $employees->pluck('employee_id'))
            ->get()
            ->groupBy('employee_id');

        $employees = $employees->map(function ($emp) use ($schedules) {
            $emp->schedules              = $schedules->get($emp->employee_id, collect())->values();
            // Castear a int: MySQL devuelve SUM/COUNT como string vía PDO
            $emp->total_appointments     = (int) $emp->total_appointments;
            $emp->appointments_today     = (int) $emp->appointments_today;
            $emp->appointments_this_week = (int) $emp->appointments_this_week;
            return $emp;
        });

        return Inertia::render('dashboard/boss_departments/Dashboard', [
            'employees'  => $employees->values(),
            'department' => $roleId === Roles::MEDICAL_DEPARTMENT ? 'medical' : 'psychology',
        ]);
    }

    public function informationNurse()
    {
        abort_unless((int) Auth::user()->role?->id === 4, 403);

        $userId = Auth::id();

        $usersInformationN = MedicalAppointment::query()
            ->select('id', 'student_id', 'created_at', 'consultation_date')
            ->where('enable', 1)
            ->where('employee_id', $userId)
            ->get();

        return Inertia::render('dashboard/nurse_role/Dashboard', [
            'medicalAppointments' => $this->dateMedicals(),
            'usersInformationN' => $usersInformationN
        ]);
    }
    public function informationPsychologist()
    {
        abort_unless((int) Auth::user()->role?->id === 5, 403);

        return Inertia::render('dashboard/psychologist_role/Dashboard', [
            //
        ]);
    }

    public function dateMedicals()
    {

        $userId = Auth::id();

        $medicalAppointments = DB::table('medical_appointments AS ma')
            ->leftJoin('users AS u', 'u.id', '=', 'ma.student_id')
            ->leftJoin('students AS s', 's.user_id', '=', 'u.id')
            ->leftJoin('student_group_assignments AS sga', 'sga.student_id', '=', 's.id')
            ->leftJoin('school_groups AS sc', 'sc.id', '=', 'sga.school_group_id')
            ->leftJoin('grades AS gd', 'gd.id', '=', 'sc.grade_id')
            ->leftJoin('groups AS gs', 'gs.id', '=', 'sc.group_id')
            ->leftJoin('careers AS cr', 'cr.id', '=', 'sc.career_id')
            ->where('ma.enable', 1)
            ->where('ma.employee_id', $userId)
            ->select(
                'ma.id',
                'u.first_name',
                'u.last_name',
                'cr.name AS career_name',
                'gd.name AS grade_name',
                'gs.name AS group_name',
                'ma.consultation_date',
                'ma.time_start',
                'ma.time_end',
                'ma.reason'
            )
            ->orderBy('ma.id', 'DESC')
            ->get();

        return $medicalAppointments;
    }
}
