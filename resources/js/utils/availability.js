export function getAvailability(schedules = [], now = new Date()) {
  const jsDay = now.getDay();            
  const dayId = jsDay === 0 ? 7 : jsDay; 

  const today = schedules.find(s => Number(s.day_of_week_id) === dayId);
  if (!today) {
    return { label: 'No trabaja hoy', color: 'gray', next: null, nextLabel: null };
  }

  const toMin = (t) => {
    if (!t) return null;
    const [h, m] = t.split(':').map(Number);
    return h * 60 + m;
  };

  const start  = toMin(today.start_time);
  const end    = toMin(today.end_time);
  const bStart = toMin(today.break_time_start);
  const bEnd   = toMin(today.break_time_end);

  if (start == null || end == null) {
    return { label: 'Sin horario definido', color: 'gray', next: null, nextLabel: null };
  }

  const nowMin = now.getHours() * 60 + now.getMinutes();

  const crossesMidnight = end < start;
  const inShift = crossesMidnight
    ? (nowMin >= start || nowMin < end)
    : (nowMin >= start && nowMin < end);

  const inBreak = bStart != null && bEnd != null && (
    bEnd < bStart
      ? (nowMin >= bStart || nowMin < bEnd)
      : (nowMin >= bStart && nowMin < bEnd)
  );

  if (inBreak) {
    return { label: 'En descanso', color: 'amber', next: today.break_time_end, nextLabel: 'Regresa a las' };
  }
  if (inShift) {
    return { label: 'Disponible ahora', color: 'green', next: today.end_time, nextLabel: 'Hasta las' };
  }
  if (nowMin < start) {
    return { label: 'Disponible hoy', color: 'blue', next: today.start_time, nextLabel: 'Entra a las' };
  }

  const next = findNextShift(schedules, dayId);
  return {
    label: 'Fuera de turno',
    color: 'gray',
    next: next?.start_time ?? null,
    nextLabel: next ? 'Próximo turno' : null,
  };
}

function findNextShift(schedules, currentDayId) {
  for (let offset = 1; offset <= 7; offset++) {
    const dayId = ((currentDayId - 1 + offset) % 7) + 1;
    const s = schedules.find(x => Number(x.day_of_week_id) === dayId);
    if (s) return s;
  }
  return null;
}