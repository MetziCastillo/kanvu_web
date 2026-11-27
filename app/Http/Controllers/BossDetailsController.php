<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Validation\ValidationException;

class BossDetailsController extends Controller
{
    public function showEmployee($employee_id)
    {
        $psychologist = DB::table('employees as e')
            ->join('users as u', 'u.id', '=', 'e.user_id')
            ->leftJoin('users_status as us', 'us.id', '=', 'u.status_id')
            ->where('e.id', $employee_id)
            ->select(
                'u.id as user_id',
                'e.id as employee_id',
                'u.first_name',
                'u.last_name',
                'u.email',
                'us.name as status_name'
            )
            ->firstOrFail();

        $stats = DB::table('psychology_appointments')
            ->where('employee_id', $psychologist->user_id)
            ->where('enable', 1)
            ->whereIn('status_id', [5, 6])
            ->selectRaw('COUNT(id) as total_consultations')
            ->selectRaw('SUM(CASE WHEN status_id = 5 THEN 1 ELSE 0 END) as attended')
            ->first();

        $totalConsultations = $stats->total_consultations ?? 0;
        $attended = $stats->attended ?? 0;
        $attendanceRate = $totalConsultations > 0 ? round(($attended / $totalConsultations) * 100) : 0;


        $startOfWeek = now()->startOfWeek();
        $weekData = [];
        for ($i = 0; $i < 7; $i++) {
            $date = $startOfWeek->copy()->addDays($i)->toDateString();
            $count = DB::table('psychology_appointments')
                ->where('employee_id', $psychologist->user_id)
                ->where('appointment_date', $date)
                ->whereIn('status_id', [5, 6])
                ->count();
            $weekData[] = $count;
        }

        $monthData = [];
        for ($i = 0; $i < 4; $i++) {
            $start = now()->startOfMonth()->addWeeks($i);
            $end = $start->copy()->endOfWeek();
            $count = DB::table('psychology_appointments')
                ->where('employee_id', $psychologist->user_id)
                ->whereBetween('appointment_date', [$start->toDateString(), $end->toDateString()])
                ->whereIn('status_id', [5, 6])
                ->count();
            $monthData[] = $count;
        }

        $yearData = [];
        for ($i = 1; $i <= 12; $i++) {
            $count = DB::table('psychology_appointments')
                ->where('employee_id', $psychologist->user_id)
                ->whereYear('appointment_date', now()->year)
                ->whereMonth('appointment_date', $i)
                ->whereIn('status_id', [5, 6])
                ->count();
            $yearData[] = $count;
        }

        $allCareers = DB::table('careers')->select('id', 'name')->get();
        $assignedCareers = DB::table('career_assignments')
            ->where('employee_id', $employee_id)
            ->where('enable', 1)
            ->pluck('career_id')
            ->toArray();

        $schedules = DB::table('employee_schedules')
            ->where('employee_id', $employee_id)
            ->orderBy('day_of_week_id')
            ->get();

        return Inertia::render('user_management/Detail_employee', [
            'psychologist' => [
                'id' => $psychologist->user_id,
                'employee_id' => $psychologist->employee_id,
                'name' => "{$psychologist->first_name} {$psychologist->last_name}",
                'email' => $psychologist->email,
                'status' => $psychologist->status_name,
                'specialty' => 'Psicología Clínica',
                'nextAvailable' => 'Hoy · 3:00 pm',
                'attendanceRate' => $attendanceRate,
                'totalConsultations' => $totalConsultations,
            ],
            'chartData' => [
                'week' => $weekData,
                'month' => $monthData,
                'year' => $yearData,
            ],
            'careers' => $allCareers,
            'assignedCareers' => $assignedCareers,
            'schedules' => $schedules
        ]);
    }

    public function updateCareers(Request $request, $employee_id)
    {
        $validated = $request->validate([
            'careers'   => ['array'],
            'careers.*' => ['exists:careers,id']
        ], [
            'careers.*.exists' => 'Una de las carreras seleccionadas no existe en el sistema.',
        ]);

        DB::transaction(function () use ($employee_id, $validated) {
            DB::table('career_assignments')->where('employee_id', $employee_id)->update(['enable' => 0]);

            foreach ($validated['careers'] ?? [] as $careerId) {
                DB::table('career_assignments')->updateOrInsert(
                    ['employee_id' => $employee_id, 'career_id' => $careerId],
                    ['enable' => 1, 'created_by_id' => Auth::id(), 'updated_at' => now()]
                );
            }
        });

        return back()->with('success', 'Carreras asignadas guardadas correctamente.');
    }

    public function updateSchedule(Request $request, $employee_id)
    {
        $validated = $request->validate([
            'schedules'                     => ['required', 'array'],
            'schedules.*.day_of_week_id'    => ['required', 'integer', 'between:1,7'],
            'schedules.*.works'             => ['required', 'boolean'],
            'schedules.*.startTime'         => ['nullable', 'date_format:H:i'],
            'schedules.*.endTime'           => ['nullable', 'date_format:H:i'],
            'schedules.*.breakStart'        => ['nullable', 'date_format:H:i'],
            'schedules.*.breakEnd'          => ['nullable', 'date_format:H:i'],
        ], [
            'schedules.required' => 'No se recibió ningún horario.',
        ]);

        DB::transaction(function () use ($employee_id, $validated) {
            foreach ($validated['schedules'] as $schedule) {
                if ($schedule['works']) {
                    if ($schedule['startTime'] >= $schedule['endTime']) {
                        throw ValidationException::withMessages(['schedules' => 'La hora de fin debe ser mayor a la de inicio.']);
                    }

                    DB::table('employee_schedules')->updateOrInsert(
                        ['employee_id' => $employee_id, 'day_of_week_id' => $schedule['day_of_week_id']],
                        [
                            'start_time'       => $schedule['startTime'],
                            'end_time'         => $schedule['endTime'],
                            'break_time_start' => $schedule['breakStart'],
                            'break_time_end'   => $schedule['breakEnd'],
                            'enable'           => 1,
                            'updated_at'       => now()
                        ]
                    );
                } else {
                    DB::table('employee_schedules')
                        ->where('employee_id', $employee_id)
                        ->where('day_of_week_id', $schedule['day_of_week_id'])
                        ->update(['enable' => 0]);
                }
            }
        });

        return back()->with('success', 'Horario actualizado correctamente.');
    }
}
