<?php

namespace App\Http\Controllers;

use App\Models\PsychologyAppointment;
use App\Models\Employee_schedule;
use App\Models\DayOfWeek;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PsychologyManagementController extends Controller
{
    private const APPOINTMENT_DURATION = 40;

    public function accept(Request $request, $id)
    {
        $userId = Auth::id();

        $appointment = PsychologyAppointment::query()
            ->where('id', $id)
            ->where('employee_id', $userId)
            ->where('status_id', 2)
            ->where('enable', 1)
            ->first();

        if (! $appointment) {
            return back()->withErrors([
                'general' => 'No se encontró la cita pendiente o no tienes permiso para aceptarla.',
            ]);
        }

        $appointment->update(['status_id' => 1]);

        return back()->with('success', 'Cita aceptada correctamente.');
    }

    public function reschedule(Request $request, $id)
    {
        $userId = Auth::id();

        $validated = $request->validate([
            'appointment_date' => ['required', 'date', 'after_or_equal:today'],
            'time_start'       => ['required', 'date_format:H:i'],
            'modality_id'      => ['required', 'exists:modalities,id'],
        ], [
            'appointment_date.required'      => 'La fecha es obligatoria.',
            'appointment_date.after_or_equal' => 'La fecha no puede ser en el pasado.',
            'time_start.required'            => 'La hora es obligatoria.',
            'time_start.date_format'         => 'La hora debe tener el formato HH:MM.',
            'modality_id.required'           => 'La modalidad es obligatoria.',
            'modality_id.exists'             => 'La modalidad seleccionada no existe.',
        ]);

        $appointment = PsychologyAppointment::query()
            ->where('id', $id)
            ->where('employee_id', $userId)
            ->where('status_id', 2)
            ->where('enable', 1)
            ->first();

        if (! $appointment) {
            return back()->withErrors([
                'general' => 'No se encontró la cita pendiente o no tienes permiso para reagendarla.',
            ]);
        }

        $newStart = Carbon::createFromFormat('Y-m-d H:i', $validated['appointment_date'] . ' ' . $validated['time_start']);
        $newEnd   = $newStart->copy()->addMinutes(self::APPOINTMENT_DURATION);

        $dayOfWeekId = $newStart->dayOfWeekIso;

        $schedule = Employee_schedule::query()
            ->where('employee_id', $userId)
            ->where('day_of_week_id', $dayOfWeekId)
            ->where('enable', 1)
            ->first();

        if (! $schedule) {
            return back()->withErrors([
                'time_start' => 'Ese día no está dentro de tus días de atención.',
            ]);
        }

        $workStart = Carbon::parse($validated['appointment_date'] . ' ' . $schedule->start_time);
        $workEnd   = Carbon::parse($validated['appointment_date'] . ' ' . $schedule->end_time);

        if ($newStart->lt($workStart) || $newEnd->gt($workEnd)) {
            return back()->withErrors([
                'time_start' => 'Esa hora está fuera de tu horario laboral.',
            ]);
        }

        if ($schedule->break_time_start && $schedule->break_time_end) {
            $breakStart = Carbon::parse($validated['appointment_date'] . ' ' . $schedule->break_time_start);
            $breakEnd   = Carbon::parse($validated['appointment_date'] . ' ' . $schedule->break_time_end);

            if ($newStart->lt($breakEnd) && $newEnd->gt($breakStart)) {
                return back()->withErrors([
                    'time_start' => 'La hora seleccionada coincide con tu horario de descanso.',
                ]);
            }
        }

        $conflict = PsychologyAppointment::query()
            ->where('employee_id', $userId)
            ->where('appointment_date', $validated['appointment_date'])
            ->where('id', '!=', $appointment->id)
            ->where('enable', 1)
            ->whereIn('status_id', [1, 2])
            ->where(function ($q) use ($newStart, $newEnd) {
                $q->where('time_start', '<', $newEnd->format('H:i:s'))
                    ->where('time_end', '>', $newStart->format('H:i:s'));
            })
            ->exists();

        if ($conflict) {
            return back()->withErrors([
                'time_start' => 'Ya existe una cita en ese horario. Elige otra hora.',
            ]);
        }

        $appointment->update([
            'appointment_date' => $validated['appointment_date'],
            'time_start'       => $newStart->format('H:i:s'),
            'time_end'         => $newEnd->format('H:i:s'),
            'modality_id'      => $validated['modality_id'],
            'status_id' => 1,
        ]);

        return back()->with('success', 'Cita reagendada correctamente.');
    }
}
