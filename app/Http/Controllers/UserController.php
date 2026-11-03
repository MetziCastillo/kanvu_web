<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use App\Models\Employee;
use App\Models\Student_group_assignment;
use App\Mail\WelcomeMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validación condicional
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email'     => 'required|email|max:255|unique:users,email',
            'role_id'   => 'required|integer|exists:roles,id',

            // Estudiante (rol 6) - El group_id que envía el front es el id de school_groups
            'matricula'  => 'required_if:role_id,6|string|max:10',
            'career_id'  => 'required_if:role_id,6|integer|exists:careers,id',
            'group_id'   => 'required_if:role_id,6|integer|exists:school_groups,id',

            // Staff (roles 1,2,3,4,5)
            'nomina'        => 'required_unless:role_id,6,1|string|max:20',
            'department_id' => 'nullable|integer|exists:departments,id',
        ]);

        $plainPassword = Str::password(12);

        // 2. Usamos una transacción para que si falla algo, no se creen usuarios a medias
        $user = DB::transaction(function () use ($validated, $plainPassword) {

            // Creamos el User base
            $user = User::create([
                'first_name' => $validated['name'],
                'last_name'  => $validated['last_name'],
                'email'      => $validated['email'],
                'id_rol'     => $validated['role_id'],
                'status_id' => 1,
                'password'   => Hash::make($plainPassword),
                'enable'     => true,
            ]);

            // Dependiendo del rol, creamos su perfil
            if ((int) $validated['role_id'] === 6) {

                // A) Creamos el estudiante
                $student = Student::create([
                    'user_id'   => $user->id,
                    'matricula' => $validated['matricula'],
                    'birthday' => '2000-01-01',
                    'number_phone' => '0000000000',
                    'number_phone_emergency' => '0000000000',
                ]);

                // B) Creamos la asignación del grupo (tu tabla pivote)
                Student_group_assignment::create([
                    'student_id'      => $student->id,
                    'school_group_id' => $validated['group_id'], // El id del frontend
                    'start_date'      => now(), // Fecha de hoy como inicio
                    'end_date'        => null,  // Sin fecha de fin
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
        // 1. Validación condicional (misma lógica que store, ignorando el propio user en unique)
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

            // Estudiante (rol 6)
            'matricula' => 'required_if:role_id,6|string|max:10',
            'career_id' => 'required_if:role_id,6|integer|exists:careers,id',
            'group_id'  => 'required_if:role_id,6|integer|exists:school_groups,id',

            // Staff (roles 2,3,4,5)
            'nomina'        => 'required_unless:role_id,6,1|string|max:20',
            'department_id' => 'nullable|integer|exists:departments,id',
        ]);

        DB::transaction(function () use ($validated, $user) {
            // 2. Actualizar datos base del usuario
            $user->update([
                'first_name' => $validated['name'],
                'last_name'  => $validated['last_name'],
                'email'      => $validated['email'],
                'id_rol'     => $validated['role_id'],
            ]);

            $newRole = (int) $validated['role_id'];

            // 3. Sincronizar perfiles según el nuevo rol
            if ($newRole === 6) {
                // --- Estudiante ---
                // Si tenía perfil de empleado, lo eliminamos
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

                // Cerrar asignaciones previas y crear la nueva
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
                // --- Staff / Admin ---
                // Si tenía perfil de estudiante, lo eliminamos
                $student = Student::where('user_id', $user->id)->first();
                if ($student) {
                    Student_group_assignment::where('student_id', $student->id)->delete();
                    $student->delete();
                }

                if ($newRole !== 1) {
                    // Roles 2,3,4,5 → empleado con nómina y departamento
                    Employee::updateOrCreate(
                        ['user_id' => $user->id],
                        [
                            'nomina'        => $validated['nomina'],
                            'department_id' => $validated['department_id'] ?? null,
                        ]
                    );
                } else {
                    // Rol 1 (admin) → solo en users, sin empleado
                    Employee::where('user_id', $user->id)->delete();
                }
            }
        });

        return response()->json([
            'user' => $user->fresh(['role']),
        ], 200);
    }
}
