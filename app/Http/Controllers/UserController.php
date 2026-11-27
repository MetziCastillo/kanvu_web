<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use App\Models\Employee;
use App\Models\Student_group_assignment;
use App\Models\Users_statu;
use App\Mail\WelcomeMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    private function bossConfig(int $roleId): ?array
    {
        return match ($roleId) {
            2 => ['role_id' => 4, 'department_id' => 3],
            3 => ['role_id' => 5, 'department_id' => 1],
            default => null,
        };
    }

    public function store(Request $request)
    {
        $auth = $request->user();
        $boss = $this->bossConfig((int) $auth->id_rol);

        if ($boss) {
            $request->merge([
                'role_id'       => $boss['role_id'],
                'department_id' => $boss['department_id'],
            ]);
        }

        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email'     => 'required|email|max:255|unique:users,email',
            'role_id'   => 'required|integer|exists:roles,id',

            'matricula' => 'required_if:role_id,6|string|max:10',
            'career_id' => 'required_if:role_id,6|integer|exists:careers,id',
            'group_id'  => 'required_if:role_id,6|integer|exists:school_groups,id',

            'nomina'        => 'required_unless:role_id,6,1|string|max:20',
            'department_id' => 'nullable|integer|exists:departments,id',
        ]);

        if ($boss && !in_array((int) $validated['role_id'], [4, 5], true)) {
            abort(403, 'No tienes permiso para crear este tipo de usuario.');
        }

        $plainPassword = Str::password(12);

        $user = DB::transaction(function () use ($validated, $plainPassword) {
            $user = User::create([
                'first_name' => $validated['name'],
                'last_name'  => $validated['last_name'],
                'email'      => $validated['email'],
                'id_rol'     => $validated['role_id'],
                'status_id'  => 1,
                'password'   => Hash::make($plainPassword),
                'enable'     => true,
            ]);

            if ((int) $validated['role_id'] === 6) {
                $student = Student::create([
                    'user_id'                => $user->id,
                    'matricula'              => $validated['matricula'],
                    'birthday'               => '2000-01-01',
                    'number_phone'           => '0000000000',
                    'number_phone_emergency' => '0000000000',
                ]);

                Student_group_assignment::create([
                    'student_id'      => $student->id,
                    'school_group_id' => $validated['group_id'],
                    'start_date'      => now(),
                    'end_date'        => null,
                ]);
            } else {
                if ((int) $validated['role_id'] !== 1) {
                    Employee::create([
                        'user_id'       => $user->id,
                        'nomina'        => $validated['nomina'],
                        'department_id' => $validated['department_id'] ?? null,
                    ]);
                }
            }

            return $user;
        });

        Mail::to($user->email)->send(new WelcomeMail($user, $plainPassword));

        return response()->json([
            'user' => $user->fresh(['role', 'student.groupAssignment']),
        ], 201);
    }

    public function update(Request $request, User $user)
    {
        $auth = $request->user();
        $boss = $this->bossConfig((int) $auth->id_rol);

        if ($boss && !in_array((int) $user->id_rol, [4, 5], true)) {
            abort(403, 'No tienes permiso para editar este usuario.');
        }

        if ($boss) {
            $validated = $request->validate([
                'name'      => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email'     => [
                    'required',
                    'email',
                    'max:255',
                    Rule::unique('users', 'email')->ignore($user->id),
                ],
                'nomina'    => 'required|string|max:20',
            ]);

            DB::transaction(function () use ($validated, $user, $boss) {
                $user->update([
                    'first_name' => $validated['name'],
                    'last_name'  => $validated['last_name'],
                    'email'      => $validated['email'],
                ]);

                $employee = Employee::where('user_id', $user->id)->first();
                if ($employee) {
                    $employee->update(['nomina' => $validated['nomina']]);
                } else {
                    Employee::create([
                        'user_id'       => $user->id,
                        'nomina'        => $validated['nomina'],
                        'department_id' => $boss['department_id'],
                    ]);
                }
            });

            return response()->json([
                'user' => $user->fresh(['role']),
            ], 200);
        }

        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email'     => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'role_id'   => 'required|integer|exists:roles,id',

            'matricula' => 'required_if:role_id,6|string|max:10',
            'career_id' => 'required_if:role_id,6|integer|exists:careers,id',
            'group_id'  => 'required_if:role_id,6|integer|exists:school_groups,id',

            'nomina'        => 'required_unless:role_id,6,1|string|max:20',
            'department_id' => 'nullable|integer|exists:departments,id',
        ]);

        DB::transaction(function () use ($validated, $user) {
            $user->update([
                'first_name' => $validated['name'],
                'last_name'  => $validated['last_name'],
                'email'      => $validated['email'],
                'id_rol'     => $validated['role_id'],
            ]);

            $newRole = (int) $validated['role_id'];

            if ($newRole === 6) {
                Employee::where('user_id', $user->id)->delete();

                $student = Student::firstOrCreate(
                    ['user_id' => $user->id],
                    [
                        'matricula'              => $validated['matricula'],
                        'birthday'               => '2000-01-01',
                        'number_phone'           => '0000000000',
                        'number_phone_emergency' => '0000000000',
                    ]
                );
                if (!$student->wasRecentlyCreated) {
                    $student->update(['matricula' => $validated['matricula']]);
                }

                Student_group_assignment::where('student_id', $student->id)
                    ->whereNull('end_date')
                    ->update(['end_date' => now()]);

                Student_group_assignment::create([
                    'student_id'      => $student->id,
                    'school_group_id' => $validated['group_id'],
                    'start_date'      => now(),
                    'end_date'        => null,
                ]);
            } else {
                $student = Student::where('user_id', $user->id)->first();
                if ($student) {
                    Student_group_assignment::where('student_id', $student->id)->delete();
                    $student->delete();
                }

                if ($newRole !== 1) {
                    Employee::updateOrCreate(
                        ['user_id' => $user->id],
                        [
                            'nomina'        => $validated['nomina'],
                            'department_id' => $validated['department_id'] ?? null,
                        ]
                    );
                } else {
                    Employee::where('user_id', $user->id)->delete();
                }
            }
        });

        return response()->json([
            'user' => $user->fresh(['role']),
        ], 200);
    }

    public function updateStatus(Request $request, User $user)
    {
        $auth = $request->user();
        $rolAuth = (int) $auth->id_rol;

        if (!in_array($rolAuth, [1, 2, 3], true)) {
            abort(403, 'No tienes permiso para cambiar el estado de usuarios.');
        }

        $validated = $request->validate([
            'status_name' => ['required', Rule::in(['Activa', 'Suspendida', 'Baja'])],
        ]);

        $boss = $this->bossConfig($rolAuth);
        if ($boss && !in_array((int) $user->id_rol, [4, 5], true)) {
            abort(403, 'No tienes permiso para gestionar este usuario.');
        }

        if ($user->status?->name === 'Baja') {
            return back()->withErrors([
                'status' => 'El usuario ya está dado de baja.',
            ]);
        }

        if ((int) $user->id_rol === 1 && $validated['status_name'] !== 'Activa') {
            return back()->withErrors([
                'status' => 'No puedes suspender ni dar de baja a un administrador.',
            ]);
        }

        if ($auth->id === $user->id && $validated['status_name'] !== 'Activa') {
            return back()->withErrors([
                'status' => 'No puedes cambiar tu propio estado.',
            ]);
        }

        $status = Users_statu::where('name', $validated['status_name'])->first();

        if (!$status) {
            return back()->withErrors([
                'status' => "El estado '{$validated['status_name']}' no existe.",
            ]);
        }

        $user->update([
            'status_id' => $status->id,
            'enable'    => $validated['status_name'] === 'Activa',
        ]);

        return back();
    }
}
