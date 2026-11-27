<script setup>
    import { ref, computed } from 'vue';
    import { usePage, router } from '@inertiajs/vue3';
    import AppIcon from '../../components/AppIcon.vue';
    import AvatarInitials from '../../components/Avatar_initials.vue';
    import Card from '../../components/Cards.vue';
    import Navbar from '../../components/navbar.vue';

    const page = usePage();

    // Datos que vienen del Backend
    const psychologist = computed(() => page.props.psychologist);
    const careers = computed(() => page.props.careers ?? []);
    const initialAssignedCareers = computed(() => page.props.assignedCareers ?? []);
    const rawSchedules = computed(() => page.props.schedules ?? []);

    // ─── ESTADO LOCAL ───
    const period = ref('Semana');
    const savedCareers = ref(false);
    const selectedCareers = ref([...initialAssignedCareers.value]);

    // Horarios: Agrupamos los días (1=Lunes a 7=Domingo) para el editor
    const daysOfWeek = [
        { id: 1, name: 'Lunes' }, { id: 2, name: 'Martes' }, { id: 3, name: 'Miércoles' },
        { id: 4, name: 'Jueves' }, { id: 5, name: 'Viernes' }, { id: 6, name: 'Sábado' }, { id: 7, name: 'Domingo' }
    ];

    const editingSchedule = ref(false);
    const draftSchedule = ref([]);

    // Inicializar horarios
    daysOfWeek.forEach(day => {
        const existing = rawSchedules.value.find(s => s.day_of_week_id === day.id);
        draftSchedule.value.push({
            day_of_week_id: day.id,
            day: day.name,
            works: !!existing,
            startTime: existing?.start_time?.slice(0, 5) ?? '09:00',
            endTime: existing?.end_time?.slice(0, 5) ?? '18:00',
            breakStart: existing?.break_time_start?.slice(0, 5) ?? '14:00',
            breakEnd: existing?.break_time_end?.slice(0, 5) ?? '15:00',
            id: existing?.id
        });
    });

    // ─── LÓGICA DE CARRERAS ───
    const toggleCareer = (careerId) => {
        savedCareers.value = false;
        if (selectedCareers.value.includes(careerId)) {
            selectedCareers.value = selectedCareers.value.filter(id => id !== careerId);
        } else {
            selectedCareers.value.push(careerId);
        }
    };

    const saveCareers = () => {
        router.post(`/boss/employees/${psychologist.value.employee_id}/careers`, {
            careers: selectedCareers.value
        }, {
            preserveScroll: true,
            onSuccess: () => savedCareers.value = true
        });
    };

    // ─── LÓGICA DE HORARIOS ───
    const saveSchedule = () => {
        router.post(`/boss/employees/${psychologist.value.employee_id}/schedule`, {
            schedules: draftSchedule.value
        }, {
            preserveScroll: true,
            onSuccess: () => editingSchedule.value = false
        });
    };

    // ─── LÓGICA DE ESTADÍSTICAS ───
    const chartData = computed(() => page.props.chartData ?? { week: [], month: [], year: [] });

    const periodValue = computed(() => {
        // Muestra el total histórico o el total del período seleccionado
        return psychologist.value.totalConsultations;
    });

    const chartValues = computed(() => {
        if (period.value === 'Semana') return chartData.value.week;
        if (period.value === 'Mes') return chartData.value.month;
        return chartData.value.year;
    });

    const chartLabels = computed(() => {
        if (period.value === 'Semana') return ['L', 'M', 'M', 'J', 'V', 'S', 'D'];
        if (period.value === 'Mes') return ['Sem 1', 'Sem 2', 'Sem 3', 'Sem 4'];
        return ['E', 'F', 'M', 'A', 'M', 'J', 'J', 'A', 'S', 'O', 'N', 'D'];
    });

    // Cálculo de alturas proporcionales para que las barras se vean bien
    const chartHeights = computed(() => {
        const values = chartValues.value;
        if (!values || values.length === 0) return [];

        const maxValue = Math.max(...values);
        if (maxValue === 0) return values.map(() => 4); // Mínimo visible

        return values.map(val => Math.max((val / maxValue) * 100, 4));
    });

    const averageDaily = computed(() => {
        const currentSum = chartValues.value.reduce((a, b) => a + b, 0);
        if (period.value === 'Semana') return Math.round(currentSum / 5);
        if (period.value === 'Mes') return Math.round(currentSum / 20);
        return Math.round(currentSum / 220);
    });
</script>

<template>
    <div class="min-h-screen flex flex-col bg-stone-50">
        <Navbar />

        <main class="flex-1 p-8">
            <!-- Header -->
            <div class="flex items-center gap-3 mb-7">
                <button @click="$inertia.visit('/dashboard')"
                    class="w-10 h-10 rounded-xl flex items-center justify-center bg-white border border-stone100 hover:bg-stone-50 transition">
                    <AppIcon name="chevronLeft" size="19" color="var(--color-stone700)" />
                </button>
                <div class="flex-1">
                    <p class="text-sm text-stone500">Detalle del personal</p>
                    <h1 class="font-display text-3xl font-semibold text-stone900">
                        {{ psychologist.name }}
                    </h1>
                </div>
                <!-- Badge de estatus -->
                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">
                    {{ psychologist.status }}
                </span>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                <!-- Columna Izquierda -->
                <div class="flex flex-col gap-6">
                    <Card>
                        <div class="flex items-center gap-4">
                            <AvatarInitials :name="psychologist.name" size="xl" />
                            <div>
                                <p class="font-semibold text-lg text-stone900">{{ psychologist.name }}</p>
                                <p class="text-sm mt-1 text-stone500">{{ psychologist.specialty }}</p>
                                <p class="text-xs mt-1 text-stone400">Nómina: {{ psychologist.employee_id }}</p>
                            </div>
                        </div>
                        <div class="mt-5 pt-5 border-t border-stone100 flex flex-col gap-3">
                            <div class="flex items-center gap-2 text-sm text-stone500">
                                <AppIcon name="mail" size="16" color="var(--color-stone400)" />
                                {{ psychologist.email }}
                            </div>
                            <div class="flex items-center gap-2 text-sm text-stone500">
                                <AppIcon name="clock" size="16" color="var(--color-stone400)" />
                                Próxima disponibilidad: {{ psychologist.nextAvailable }}
                            </div>
                        </div>
                    </Card>

                    <Card>
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <p class="font-semibold text-stone900">Carreras asignadas</p>
                                <p class="text-xs mt-1 text-stone500">Selecciona las carreras que atenderá.</p>
                            </div>
                            <span v-if="savedCareers" class="text-xs font-semibold text-olive600">Guardado</span>
                        </div>
                        <div class="flex flex-col gap-2 max-h-64 overflow-y-auto">
                            <button v-for="career in careers" :key="career.id" @click="toggleCareer(career.id)"
                                class="flex items-center gap-3 p-3 rounded-xl text-left transition-colors"
                                :class="selectedCareers.includes(career.id) ? 'bg-olive50' : 'bg-stone100'">
                                <span class="w-5 h-5 rounded-md flex items-center justify-center border"
                                    :class="selectedCareers.includes(career.id) ? 'bg-olive600 border-olive600' : 'bg-white border-stone300'">
                                    <AppIcon v-if="selectedCareers.includes(career.id)" name="check" size="12"
                                        color="white" />
                                </span>
                                <span class="text-sm font-medium"
                                    :class="selectedCareers.includes(career.id) ? 'text-olive700' : 'text-stone500'">
                                    {{ career.name }}
                                </span>
                            </button>
                        </div>
                        <div class="mt-4">
                            <button @click="saveCareers"
                                class="w-full py-2 bg-olive600 text-white rounded-xl text-sm font-semibold hover:brightness-110 transition">
                                Guardar asignación
                            </button>
                        </div>
                    </Card>
                </div>

                <!-- Columna Derecha -->
                <div class="xl:col-span-2 flex flex-col gap-6">
                    <!-- Gráfica -->
                    <Card>
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
                            <div>
                                <p class="font-semibold text-stone900">Estadísticas de consultas</p>
                                <p class="text-sm mt-1 text-stone500">Actividad y asistencia del periodo seleccionado.
                                </p>
                            </div>
                            <div class="flex gap-1 p-1 rounded-xl bg-stone100">
                                <button v-for="option in ['Semana', 'Mes', 'Año']" :key="option"
                                    @click="period = option"
                                    class="px-4 py-2 rounded-lg text-xs font-semibold transition-all"
                                    :class="period === option ? 'bg-white text-olive700 shadow-sm' : 'text-stone500 hover:text-stone700'">
                                    {{ option }}
                                </button>
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-3 mb-7">
                            <div class="rounded-xl p-4 bg-olive50">
                                <p class="font-display text-2xl font-semibold text-stone900">{{ periodValue }}</p>
                                <p class="text-xs mt-1 text-stone500">Consultas</p>
                            </div>
                            <div class="rounded-xl p-4 bg-olive50">
                                <p class="font-display text-2xl font-semibold text-stone900">{{
                                    psychologist.attendanceRate }}%</p>
                                <p class="text-xs mt-1 text-stone500">Asistencia</p>
                            </div>
                            <div class="rounded-xl p-4 bg-olive50">
                                <p class="font-display text-2xl font-semibold text-stone900">{{ averageDaily }}</p>
                                <p class="text-xs mt-1 text-stone500">Promedio diario</p>
                            </div>
                        </div>

                        <div class="h-44 flex items-end gap-2 sm:gap-3">
                            <div v-for="(value, index) in chartValues" :key="index"
                                class="flex-1 flex flex-col items-center justify-end gap-2 h-full">
                                <!-- Usamos chartHeights para la altura real -->
                                <div class="w-full rounded-t-lg min-h-2 transition-all"
                                    :style="{ height: `${chartHeights[index]}%`, background: index === chartValues.length - 1 ? 'var(--color-olive600)' : 'var(--color-olive200)' }">
                                </div>
                                <span class="text-[10px] font-semibold text-stone400">{{ chartLabels[index] }}</span>
                            </div>
                        </div>
                    </Card>

                    <!-- Editor de Horario -->
                    <Card padding="p-0">
                        <div
                            class="px-6 py-5 border-b border-stone100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div>
                                <p class="font-semibold text-stone900">Asignación de horario</p>
                                <p class="text-sm mt-1 text-stone500">Define días laborales, jornada y horario de
                                    descanso.</p>
                            </div>
                            <button @click="editingSchedule = !editingSchedule"
                                class="px-4 py-2 rounded-xl text-sm font-semibold transition-all"
                                :class="editingSchedule ? 'bg-stone200 text-stone700' : 'bg-olive600 text-white hover:brightness-110'">
                                {{ editingSchedule ? 'Cancelar edición' : 'Editar horario' }}
                            </button>
                        </div>

                        <div class="overflow-x-auto">
                            <div class="min-w-[720px]">
                                <!-- Cabecera Tabla -->
                                <div class="grid grid-cols-[140px_110px_1fr_1fr] gap-4 px-6 py-3 bg-olive50">
                                    <p class="text-xs font-bold uppercase tracking-wider text-stone500">Día</p>
                                    <p class="text-xs font-bold uppercase tracking-wider text-stone500">Laborable</p>
                                    <p class="text-xs font-bold uppercase tracking-wider text-stone500">Jornada</p>
                                    <p class="text-xs font-bold uppercase tracking-wider text-stone500">Descanso</p>
                                </div>

                                <!-- Filas de Días -->
                                <div v-for="(item, index) in draftSchedule" :key="item.day_of_week_id"
                                    class="grid grid-cols-[140px_110px_1fr_1fr] gap-4 items-center px-6 py-4 border-t border-stone100">
                                    <p class="text-sm font-semibold text-stone900">{{ item.day }}</p>

                                    <!-- Switch -->
                                    <button :disabled="!editingSchedule" @click="item.works = !item.works"
                                        class="w-11 h-6 rounded-full relative transition-all disabled:cursor-default"
                                        :class="item.works ? 'bg-olive500' : 'bg-stone300'">
                                        <span
                                            class="absolute top-0.5 w-5 h-5 rounded-full bg-white shadow-sm transition-all"
                                            :class="item.works ? 'left-[1.375rem]' : 'left-[0.125rem]'"></span>
                                    </button>

                                    <!-- Inputs de Hora (Si trabaja) -->
                                    <template v-if="item.works">
                                        <div class="flex items-center gap-2">
                                            <input type="time" v-model="item.startTime" :disabled="!editingSchedule"
                                                class="w-full rounded-lg border border-stone300 px-3 py-2 text-sm disabled:bg-stone-50" />
                                            <span class="text-xs text-stone400">a</span>
                                            <input type="time" v-model="item.endTime" :disabled="!editingSchedule"
                                                class="w-full rounded-lg border border-stone300 px-3 py-2 text-sm disabled:bg-stone-50" />
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <input type="time" v-model="item.breakStart" :disabled="!editingSchedule"
                                                class="w-full rounded-lg border border-stone300 px-3 py-2 text-sm disabled:bg-stone-50" />
                                            <span class="text-xs text-stone400">a</span>
                                            <input type="time" v-model="item.breakEnd" :disabled="!editingSchedule"
                                                class="w-full rounded-lg border border-stone300 px-3 py-2 text-sm disabled:bg-stone-50" />
                                        </div>
                                    </template>
                                    <p v-else class="col-span-2 text-sm italic text-stone400">Día de descanso</p>
                                </div>
                            </div>
                        </div>

                        <!-- Botón Guardar Horario -->
                        <div v-if="editingSchedule"
                            class="px-6 py-4 border-t border-stone100 bg-stone100 flex justify-end">
                            <button @click="saveSchedule"
                                class="px-4 py-2 bg-olive600 text-white rounded-xl text-sm font-semibold flex items-center gap-2 hover:brightness-110 transition">
                                <AppIcon name="check" size="16" color="white" />
                                Guardar horario
                            </button>
                        </div>
                    </Card>
                </div>
            </div>
        </main>
    </div>
</template> 