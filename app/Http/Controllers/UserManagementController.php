<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Career;
use App\Models\Department;
use App\Models\School_group;
use Inertia\Inertia;

class UserManagementController extends Controller
{
    public function index()
    {
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
                'department' => $department
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
}
