<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Career;
use App\Models\Department;
use App\Models\School_group;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Support\Roles;


class UserManagementController extends Controller
{
    public function index()
    {

        $roleId = (int) Auth::user()->role?->id;

        $roles = Role::query()
            ->select('id', 'name')
            ->get();

        $careers = Career::query()
            ->select('id', 'name')
            ->where('enable', true)
            ->get();

        $department = Department::query()
            ->select('id', 'name')
            ->get();

        return Inertia::render(
            'user_management/Dashboard_um',
            [
                'roles' => $roles,
                'careers' => $careers,
                'department' => $department,
                'allUsers' => $this->AllUsers($roleId),
            ]
        );
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


    public function AllUsers(int $roleId)
    {
        $latestSga = DB::table('student_group_assignments as sga2')
            ->select('sga2.student_id', DB::raw('MAX(sga2.id) as last_id'))
            ->groupBy('sga2.student_id');

        $query = DB::table('users as us')
            ->leftJoin('roles as r', 'r.id', '=', 'us.id_rol')
            ->leftJoin('users_status as uss', 'uss.id', '=', 'us.status_id')
            ->leftJoin('students as sd', 'sd.user_id', '=', 'us.id')
            ->leftJoinSub($latestSga, 'last_sga', 'last_sga.student_id', '=', 'sd.id')
            ->leftJoin('student_group_assignments as sga', 'sga.id', '=', 'last_sga.last_id')
            ->leftJoin('school_groups as sc', 'sc.id', '=', 'sga.school_group_id')
            ->leftJoin('careers as c', 'c.id', '=', 'sc.career_id')
            ->leftJoin('groups as g', 'g.id', '=', 'sc.group_id')
            ->leftJoin('grades as gd', 'gd.id', '=', 'sc.grade_id')
            ->leftJoin('employees as ep', 'ep.user_id', '=', 'us.id')
            ->leftJoin('departments as dep', 'dep.id', '=', 'ep.department_id')
            ->select(
                'us.id as user_id',
                'us.first_name as name',
                'us.last_name',
                'us.email',
                'us.id_rol',
                'us.status_id',
                'uss.name as status_name',
                'sd.matricula',
                'ep.nomina',
                'us.enable',
                'r.name as rol_name',
                'us.id as student_id', 
                'sc.id as group_id',
                'sc.career_id',
                'c.name as career_name',
                'gd.name as grade_name',
                'g.name as group_name',
                'ep.id as employee_id',
                'ep.department_id',
                'dep.name as department_name'
            );

        switch ($roleId) {
            case Roles::ADMIN:
                break;

            case Roles::MEDICAL_DEPARTMENT:
                $query->where('us.id_rol', Roles::NURSE);
                break;

            case Roles::PSYCHOLOGY_DEPARTMENT:
                $query->where('us.id_rol', Roles::PSYCHOLOGIST);
                break;

            default:
                $query->whereRaw('1 = 0');
                break;
        }

        return $query->orderBy('us.id', 'desc')->get();
    }
}
