<?php

namespace App\Http\Controllers;

use App\Models\MedicalAppointment;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;

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
        $roleId = (int) Auth::user()->role?->id;

        abort_unless(in_array($roleId, [2, 3], true), 403);
        $usersInformationBossM = User::query()
            ->select('id', 'status_id', 'id_rol')
            ->where('id_rol', 4)
            ->get();

        $usersInformationBossP = User::query()
            ->select('id', 'status_id', 'id_rol')
            ->where('id_rol', 5)
            ->get();

        return Inertia::render('dashboard/boss_departments/Dashboard', [
            'usersInformationBossM' => $usersInformationBossM,
            'usersInformationBossP' => $usersInformationBossP
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
