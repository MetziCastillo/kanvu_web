<script setup>
    import { computed, ref } from 'vue';
    import { usePage, router } from '@inertiajs/vue3';
    import Card from '../../components/Cards.vue';
    import AvatarInitials from '../../components/Avatar_initials.vue';
    import AppIcon from '../../components/AppIcon.vue';
    import InputLabel from '../../components/Input_labels.vue';

    const page = usePage();
    const psychologyAppointments = computed(() => page.props.psychologyAppointments ?? []);
    const modalitiesV = computed(() => page.props.modalities ?? []);

    const valuesT = [
        { title: 'Preferencia', value: 'appointment_date', icon: 'calendar' },
        { title: 'Hora', value: 'time_start', icon: 'clock' },
        { title: 'Modalidad', value: 'modality_name', icon: null },
        { title: 'Contacto', value: 'email', icon: 'mail' },
    ];

    const getModalityIcon = (modality) => modality === 'En línea' ? 'monitor' : 'mapPin';

    const getIcon = (item, field) => {
        if (field.title === 'Modalidad') return getModalityIcon(item.modality_name);
        return field.icon;
    };

    // Formatear la hora para quitar los segundos en la vista (14:30:00 -> 14:30)
    const formatTime = (time) => time ? time.slice(0, 5) : '';

    // ─── Estado reagendar ───
    const reschedulingId = ref(null);
    const form = ref({ date: '', time: '', modality_id: '' });
    const isSaving = ref(false);
    const errors = ref({});

    const openReschedule = (item) => {
        reschedulingId.value = item.appointment_id;
        form.value = {
            date: item.appointment_date ?? '',
            time: formatTime(item.time_start), // Recortamos los segundos
            modality_id: item.modality_id ?? '',
        };
        errors.value = {};
    };

    const closeReschedule = () => {
        reschedulingId.value = null;
        form.value = { date: '', time: '', modality_id: '' };
        errors.value = {};
    };

    const canSave = computed(() => !!form.value.date && !!form.value.time && !!form.value.modality_id);

    const saveReschedule = (item) => {
        if (!canSave.value || isSaving.value) return;

        isSaving.value = true;
        errors.value = {};

        // Si NO usas Ziggy, cambia esto por: `/psychology/appointments/${item.appointment_id}/reschedule`
        router.put(
            `/psychology/appointments/${item.appointment_id}/reschedule`,
            {
                appointment_date: form.value.date,
                time_start: form.value.time,
                modality_id: form.value.modality_id,
            },
            {
                preserveScroll: true,
                onSuccess: () => closeReschedule(),
                onError: (err) => { errors.value = err; },
                onFinish: () => { isSaving.value = false; },
            }
        );
    };

    const acceptAppointment = (item) => {
        router.put(
            `/psychology/appointments/${item.appointment_id}/accept`,
            {},
            { preserveScroll: true }
        );
    };
</script>

<template>
    <div class="mb-8">
        <!-- Alerta de éxito (Flash) -->
        <div v-if="page.props.flash?.success"
            class="mb-4 rounded-xl bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-2">
            {{ page.props.flash.success }}
        </div>

        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-3 mb-4">
            <div>
                <div class="flex items-center gap-2">
                    <p class="font-display text-2xl font-semibold text-stone900">
                        Citas externas por reagendar
                    </p>
                    <span v-if="psychologyAppointments.length > 0"
                        class="text-xs font-semibold px-2.5 py-1 rounded-full bg-sand text-[#7a6a30]">
                        {{ psychologyAppointments.length }} pendientes
                    </span>
                </div>
                <p class="text-sm mt-1 text-stone500">
                    Ajusta la fecha, hora o modalidad de las primeras citas de alumnos externos.
                </p>
            </div>
        </div>

        <!-- Grid -->
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-5">
            <Card v-for="item in psychologyAppointments" :key="item.appointment_id" padding="p-0"
                class="overflow-hidden">
                <!-- Barra superior -->
                <div class="h-1.5 bg-sandDeep" />

                <div class="p-5">
                    <!-- Cabecera de la tarjeta -->
                    <div class="flex items-start gap-4">
                        <AvatarInitials :name="`${item.first_name} ${item.last_name}`" size="lg" />
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <p class="font-semibold text-stone900">
                                        {{ item.first_name }} {{ item.last_name }}
                                    </p>
                                    <p class="text-xs mt-1 text-stone500">
                                        Alumno externo · Psicología
                                    </p>
                                </div>
                                <span
                                    class="text-xs font-semibold px-2.5 py-1 rounded-full bg-sand text-[#7a6a30] whitespace-nowrap">
                                    Pendiente de reagendar
                                </span>
                            </div>
                            <p class="text-sm leading-relaxed mt-3 text-stone700">
                                {{ item.reason }}
                            </p>
                        </div>
                    </div>

                    <!-- Grid de datos -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mt-5 pt-4 border-t border-stone100">
                        <div v-for="it in valuesT" :key="it.title" class="min-w-0">
                            <div class="flex items-center gap-1.5">
                                <AppIcon :name="getIcon(item, it)" size="13" color="var(--color-stone400)" />
                                <p class="text-[10px] uppercase tracking-wider text-stone400">
                                    {{ it.title }}
                                </p>
                            </div>
                            <p class="text-xs font-semibold mt-1 truncate text-stone700">
                                <!-- Formateamos la hora si es el campo time_start -->
                                {{ it.value === 'time_start' ? formatTime(item[it.value]) : item[it.value] }}
                            </p>
                        </div>
                    </div>

                    <!-- ─── MODO REAGENDAR ─── -->
                    <template v-if="reschedulingId === item.appointment_id">
                        <div class="mt-5 pt-5 border-t border-stone100">
                            <p class="text-xs font-bold uppercase tracking-wider mb-3 text-stone400">
                                Nuevo horario
                            </p>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <InputLabel title="Fecha" label="date" type="date" v-model="form.date" />
                                <InputLabel title="Hora" label="time_start" type="time" v-model="form.time" />
                                <div class="flex flex-col gap-1.5">
                                    <label class="text-xs font-semibold uppercase tracking-wider text-stone500">
                                        Modalidad
                                    </label>
                                    <select v-model="form.modality_id"
                                        class="rounded-xl border bg-white px-4 py-3 text-sm border-stone300 focus:outline-none focus:ring-2 focus:ring-olive600/30 focus:border-olive600 transition">
                                        <option value="" disabled>Seleccione</option>
                                        <option v-for="m in modalitiesV" :value="m.id" :key="m.id">
                                            {{ m.name }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <!-- Errores -->
                            <div v-if="errors.general || errors.appointment_date || errors.time_start || errors.modality_id"
                                class="mt-3 p-3 bg-red-50 border border-red-200 rounded-xl">
                                <p v-if="errors.general" class="text-xs text-red-600 font-semibold">{{ errors.general }}
                                </p>
                                <p v-if="errors.appointment_date" class="text-xs text-red-600 font-semibold">{{
                                    errors.appointment_date }}</p>
                                <p v-if="errors.time_start" class="text-xs text-red-600 font-semibold">{{
                                    errors.time_start }}</p>
                                <p v-if="errors.modality_id" class="text-xs text-red-600 font-semibold">{{
                                    errors.modality_id }}</p>
                            </div>

                            <!-- Acciones -->
                            <div class="flex justify-end gap-2 mt-4">
                                <button type="button" @click="closeReschedule"
                                    class="rounded-xl font-semibold transition-all active:scale-[0.97] select-none cursor-pointer inline-flex items-center justify-center gap-2 px-4 py-2 text-sm text-olive600 bg-transparent hover:bg-sand/60">
                                    Cancelar
                                </button>
                                <button type="button" @click="saveReschedule(item)" :disabled="!canSave || isSaving"
                                    class="px-4 py-2 text-sm rounded-xl font-semibold transition-all active:scale-[0.97] select-none inline-flex items-center justify-center gap-2 bg-olive600 text-white hover:brightness-110"
                                    :class="(!canSave || isSaving) ? 'opacity-40 cursor-not-allowed' : 'cursor-pointer'">
                                    <AppIcon name="check" size="15" color="white" />
                                    {{ isSaving ? 'Guardando…' : 'Guardar cambio' }}
                                </button>
                            </div>
                        </div>
                    </template>

                    <!-- ─── MODO NORMAL ─── -->
                    <template v-else>
                        <div class="flex justify-end gap-2 mt-5">
                            <button type="button" @click="acceptAppointment(item)"
                                class="px-4 py-2 text-sm rounded-xl font-semibold transition-all active:scale-[0.97] select-none cursor-pointer inline-flex items-center justify-center gap-2 bg-olive600 text-white hover:brightness-110">
                                <AppIcon name="check" size="15" color="white" />
                                Aceptar cita
                            </button>
                            <button type="button" @click="openReschedule(item)"
                                class="px-4 py-2 text-sm rounded-xl font-semibold transition-all active:scale-[0.97] select-none cursor-pointer inline-flex items-center justify-center gap-2 bg-olive600 text-white hover:brightness-110">
                                <AppIcon name="calendar" size="15" color="white" />
                                Reagendar cita
                            </button>
                        </div>
                    </template>
                </div>
            </Card>
        </div>
    </div>
</template>