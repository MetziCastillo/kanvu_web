<script setup>
    import { computed, ref, watch } from 'vue';
    import { usePage, router } from '@inertiajs/vue3';

    import AppIcon from '../../components/AppIcon.vue';
    import Navbar from '../../components/navbar.vue';
    import InputLabel from '../../components/Input_labels.vue';

    const props = defineProps({
        roles: { type: Array, required: true },
        careers: { type: Array, required: true },
        department: { type: Array, default: () => [] },
    });

    const page = usePage();


    // ── Búsqueda por matrícula ──────────────────────────────
    const matricula = ref('');
    const searchStatus = ref('idle'); // idle | searching | found | not-found
    const manualMode = ref(false);

    // ── Datos del estudiante ────────────────────────────────
    const studentId = ref(null); // ✨ NUEVO: Guarda el ID real del estudiante
    const studentName = ref('');
    const studentLastName = ref('');
    const studentCareerId = ref('');
    const studentGroupId = ref('');

    // ── Selects ─────────────────────────────────────────────
    const groups = ref([]);
    const loadingGroups = ref(false);
    const selectedCareer = ref('');
    const selectedGroup = ref('');

    // ── Datos de la consulta ────────────────────────────────
    const appointmentDate = ref('');
    const startTime = ref('');
    const endTime = ref('');
    const discomfort = ref('');
    const indications = ref('');
    const observations = ref('');

    // ── Estado ──────────────────────────────────────────────
    const errors = ref({});
    const saving = ref(false);

    // ── Derivados ───────────────────────────────────────────
    const studentResolved = computed(
        () => searchStatus.value === 'found' || manualMode.value
    );

    const isStudentLocked = computed(
        () => searchStatus.value === 'found' && !manualMode.value
    );

    const canEditManual = computed(() => manualMode.value);
    const canEditMatricula = computed(() => !isStudentLocked.value);
    const hasErrors = computed(() => Object.keys(errors.value).length > 0);

    const errorMessage = computed(() => {
        if (errors.value.general?.[0]) return errors.value.general[0];
        return 'Busca un estudiante o regístralo manualmente y completa los campos requeridos.';
    });

    // ── Cargar grupos por carrera ───────────────────────────
    const loadGroups = async (careerId) => {
        groups.value = [];
        if (!careerId) return;
        loadingGroups.value = true;
        try {
            const res = await fetch(`/groups/by-career/${careerId}`, {
                headers: { Accept: 'application/json' },
                credentials: 'same-origin',
            });
            if (!res.ok) throw new Error('Error al cargar grupos');
            groups.value = await res.json();
        } catch (e) {
            console.error(e);
            groups.value = [];
        } finally {
            loadingGroups.value = false;
        }
    };

    watch(selectedCareer, async (id, old) => {
        if (id === old) return;
        selectedGroup.value = '';
        await loadGroups(id);
    });

    // ── Buscar estudiante por matrícula ─────────────────────
    const searchStudent = async () => {
        if (isStudentLocked.value) return;

        const m = matricula.value.trim();
        if (!m) return;

        searchStatus.value = 'searching';
        errors.value = {};

        try {
            const res = await fetch(
                `/students/by-matricula/${encodeURIComponent(m)}`,
                {
                    headers: { Accept: 'application/json' },
                    credentials: 'same-origin',
                }
            );

            if (res.status === 404) {
                searchStatus.value = 'not-found';
                return;
            }
            if (!res.ok) throw new Error('Error al buscar estudiante');

            const s = await res.json();

            // ✨ Asignamos el ID del estudiante que viene del backend
            studentId.value = s.student_id;
            studentName.value = s.name ?? '';
            studentLastName.value = s.last_name ?? '';
            studentCareerId.value = s.career_id ?? '';
            studentGroupId.value = s.group_id ?? '';

            selectedCareer.value = s.career_id ?? '';
            await loadGroups(s.career_id ?? '');
            selectedGroup.value = s.group_id ?? '';

            searchStatus.value = 'found';
        } catch (e) {
            console.error(e);
            searchStatus.value = 'idle';
            errors.value = { general: ['Error al buscar la matrícula.'] };
        }
    };

    // ── Modo manual ─────────────────────────────────────────
    const enableManualRegistration = () => {
        manualMode.value = true;
        searchStatus.value = 'idle';
        errors.value = {};

        matricula.value = '';
        studentId.value = null; // ✨ Limpiamos el ID
        studentName.value = '';
        studentLastName.value = '';
        studentCareerId.value = '';
        studentGroupId.value = '';

        selectedCareer.value = '';
        selectedGroup.value = '';
        groups.value = [];
    };

    // ── Resetear búsqueda ───────────────────────────────────
    const resetSearch = () => {
        searchStatus.value = 'idle';
        manualMode.value = false;
        errors.value = {};

        matricula.value = '';
        studentId.value = null; // ✨ Limpiamos el ID
        studentName.value = '';
        studentLastName.value = '';
        studentCareerId.value = '';
        studentGroupId.value = '';
        selectedCareer.value = '';
        selectedGroup.value = '';
        groups.value = [];
    };

    const cancel = () => {
        router.back();
    };

    // ── Validación ──────────────────────────────────────────
    const isValid = computed(() => {
        if (!appointmentDate.value) return false;
        if (!startTime.value || !endTime.value) return false;
        if (!discomfort.value.trim()) return false;

        if (manualMode.value) {
            if (!studentName.value.trim()) return false;
            if (!studentLastName.value.trim()) return false;
            if (!selectedCareer.value || !selectedGroup.value) return false;
        } else {
            if (searchStatus.value !== 'found') return false;
            if (!studentGroupId.value) return false;
        }
        return true;
    });

    // ── Submit ──────────────────────────────────────────────
    const submitAppointment = async () => {
        if (!isValid.value || saving.value) return;

        saving.value = true;
        errors.value = {};

        const payload = {
            matricula: manualMode.value ? null : matricula.value.trim(),
            manual: manualMode.value,
            student_id: studentId.value, // ✨ Enviamos el ID al backend
            student_name: studentName.value.trim(),
            student_last_name: studentLastName.value.trim(),
            career_id: manualMode.value
                ? Number(selectedCareer.value)
                : Number(studentCareerId.value),
            group_id: manualMode.value
                ? Number(selectedGroup.value)
                : Number(studentGroupId.value),
            appointment_date: appointmentDate.value, // El backend lo guardará como consultation_date
            start_time: startTime.value,             // El backend lo guardará como time_start
            end_time: endTime.value,                 // El backend lo guardará como time_end
            discomfort: discomfort.value.trim(),     // El backend lo guardará como reason
            indications: indications.value.trim(),   // El backend lo guardará como instructions
            observations: observations.value.trim(), // El backend lo guardará como notes
        };

        try {
            const csrfToken = document
                .querySelector('meta[name="csrf-token"]')
                ?.getAttribute('content');

            const res = await fetch('/appointments/medical', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': csrfToken ?? '',
                },
                credentials: 'same-origin',
                body: JSON.stringify(payload),
            });

            const data = await res.json().catch(() => ({}));

            if (!res.ok) {
                if (res.status === 422 && data.errors) {
                    errors.value = data.errors;
                } else {
                    errors.value = {
                        general: [data.message ?? 'Error al guardar la cita.'],
                    };
                }
                return;
            }

            router.visit('/dashboard/nurse');
        } catch (e) {
            console.error(e);
            errors.value = { general: ['Error de conexión al guardar la cita.'] };
        } finally {
            saving.value = false;
        }
    };
</script>

<template>
    <!-- El template se mantiene exactamente igual que en tu archivo -->
    <div class="min-h-screen flex flex-col">
        <Navbar />

        <main class="flex-1 p-8">
            <div class="max-w-4xl mx-auto">

                <!-- Header -->
                <div class="flex items-center gap-3 mb-8">
                    <button @click="cancel"
                        class="w-9 h-9 rounded-xl flex items-center justify-center hover:bg-stone-100 transition-colors bg-white border border-stone-100">
                        <AppIcon name="chevronLeft" :size="18" color="var(--color-stone700)" />
                    </button>
                    <div>
                        <h1 class="text-2xl font-semibold text-stone-900">Registrar cita</h1>
                        <p class="text-sm text-stone-500">
                            Busca al estudiante y completa los datos de la consulta médica
                        </p>
                    </div>
                </div>

                <div class="p-8 bg-white rounded-2xl hover:shadow-sm transition-shadow border border-stone-100">
                    <div class="flex flex-col gap-8">

                        <!-- ── Identificación del estudiante ───────────── -->
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider mb-4 text-stone-400">
                                Identificación del estudiante
                            </p>

                            <div class="rounded-2xl p-5 bg-stone-100">
                                <label class="text-xs font-semibold uppercase tracking-wider text-stone-500">
                                    Buscar por matrícula
                                </label>

                                <div class="flex flex-col sm:flex-row gap-3 mt-2">
                                    <div class="flex-1">
                                        <InputLabel label="matricula" placeholder="Ej. A01234567" type="text"
                                            icon-component="shield" v-model="matricula" :readonly="!canEditMatricula"
                                            @keydown.enter="searchStudent" />
                                    </div>

                                    <button v-if="!isStudentLocked" @click="searchStudent"
                                        :disabled="!matricula.trim() || searchStatus === 'searching'"
                                        class="px-6 py-3 text-sm bg-olive600 text-white rounded-xl font-semibold transition-all active:scale-[0.97] select-none cursor-pointer inline-flex items-center justify-center gap-2 disabled:opacity-40 disabled:cursor-not-allowed self-end">
                                        {{ searchStatus === 'searching' ? 'Buscando...' : 'Buscar estudiante' }}
                                    </button>

                                    <button v-else @click="resetSearch"
                                        class="px-6 py-3 text-sm bg-white text-olive700 border-[1.5px] border-olive300 rounded-xl font-semibold transition-all active:scale-[0.97] select-none cursor-pointer inline-flex items-center justify-center gap-2 self-end">
                                        Cambiar estudiante
                                    </button>
                                </div>

                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mt-4">
                                    <p class="text-xs text-stone-500">
                                        También puedes registrar alumnos invitados o externos.
                                    </p>
                                    <button v-if="!isStudentLocked" @click="enableManualRegistration"
                                        class="text-xs font-semibold px-4 py-2 rounded-lg bg-white text-olive700">
                                        Registrar manualmente
                                    </button>
                                </div>
                            </div>

                            <!-- Estado: no encontrado -->
                            <div v-if="searchStatus === 'not-found' && !manualMode"
                                class="mt-4 rounded-xl p-4 flex flex-col sm:flex-row sm:items-center gap-3 bg-sand">
                                <AppIcon name="Bell" :size="18" color="#7a6a30" />
                                <p class="text-sm flex-1 text-[#7a6a30]">
                                    No encontramos esta matrícula en el directorio escolar.
                                </p>
                                <button @click="enableManualRegistration"
                                    class="rounded-xl font-semibold transition-all active:scale-[0.97] select-none cursor-pointer inline-flex items-center justify-center gap-2 border-[1.5px] border-olive300 text-olive700 bg-transparent px-4 py-2 text-sm">
                                    Registrar manualmente
                                </button>
                            </div>

                            <!-- Estado: encontrado -->
                            <div v-if="searchStatus === 'found'"
                                class="mt-4 rounded-xl p-4 flex items-center gap-3 bg-olive50">
                                <div class="w-9 h-9 rounded-xl flex items-center justify-center bg-olive100">
                                    <AppIcon name="check" :size="17" color="var(--color-olive700)" />
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-stone-700">Estudiante encontrado</p>
                                    <p class="text-xs mt-0.5 text-olive600">
                                        Los datos académicos se completaron automáticamente y son de solo lectura.
                                    </p>
                                </div>
                            </div>

                            <!-- Grid de datos del estudiante -->
                            <div v-if="studentResolved" class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-5">
                                <InputLabel title="Nombre del estudiante" label="student_name"
                                    placeholder="Nombre completo" type="text" v-model="studentName"
                                    :readonly="isStudentLocked || !canEditManual" />

                                <InputLabel title="Apellidos del estudiante" label="student_last_name"
                                    placeholder="Apellidos" type="text" v-model="studentLastName"
                                    :readonly="isStudentLocked || !canEditManual" />

                                <!-- Carrera -->
                                <div class="flex flex-col gap-1.5">
                                    <label class="text-xs font-semibold uppercase tracking-wider text-stone-500">
                                        Carrera
                                    </label>
                                    <select v-model="selectedCareer" name="career_id"
                                        :disabled="isStudentLocked || !canEditManual"
                                        class="w-full rounded-xl border bg-white text-sm outline-none py-3 px-4 border-stone-300 text-stone-900 disabled:opacity-60 disabled:cursor-not-allowed">
                                        <option value="" disabled>Selecciona una carrera</option>
                                        <option v-for="career in careers" :key="career.id" :value="career.id">
                                            {{ career.name }}
                                        </option>
                                    </select>
                                    <p v-if="errors.career_id" class="text-xs text-red-600">
                                        {{ errors.career_id[0] }}
                                    </p>
                                </div>

                                <!-- Grado y grupo -->
                                <div class="flex flex-col gap-1.5">
                                    <label class="text-xs font-semibold uppercase tracking-wider text-stone-500">
                                        Grado y grupo
                                    </label>
                                    <select v-model="selectedGroup" name="group_id"
                                        :disabled="isStudentLocked || !canEditManual || loadingGroups || !selectedCareer"
                                        class="w-full rounded-xl border bg-white text-sm outline-none py-3 px-4 border-stone-300 text-stone-900 disabled:opacity-50 disabled:cursor-not-allowed">
                                        <option value="" class="text-stone-300" disabled>
                                            {{ loadingGroups ? 'Cargando...' : 'Selecciona un grupo' }}
                                        </option>
                                        <option v-for="g in groups" :key="g.id" :value="g.id">
                                            {{ g.grade_name }} — {{ g.group_name }}
                                        </option>
                                    </select>
                                    <p v-if="errors.group_id" class="text-xs text-red-600">
                                        {{ errors.group_id[0] }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- ── Datos de la consulta ───────────────────── -->
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider mb-4 text-stone-400">
                                Datos de la consulta
                            </p>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                <InputLabel title="Fecha de la consulta" label="appointment_date" placeholder=""
                                    type="date" v-model="appointmentDate" />
                                <InputLabel title="Hora de inicio" label="start_time" placeholder="" type="time"
                                    v-model="startTime" />
                                <InputLabel title="Hora de término" label="end_time" placeholder="" type="time"
                                    v-model="endTime" />
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="flex flex-col gap-1.5">
                                    <label class="text-xs font-semibold uppercase tracking-wider text-stone-500">
                                        Malestar / Motivo de la consulta
                                    </label>
                                    <textarea v-model="discomfort"
                                        placeholder="Describe el malestar o motivo de la consulta..."
                                        class="rounded-xl border px-4 py-3 text-sm outline-none resize-none h-36 text-stone-900 border-stone-300"></textarea>
                                    <p v-if="errors.discomfort" class="text-xs text-red-600">
                                        {{ errors.discomfort[0] }}
                                    </p>
                                </div>

                                <div class="flex flex-col gap-1.5">
                                    <label class="text-xs font-semibold uppercase tracking-wider text-stone-500">
                                        Indicaciones
                                    </label>
                                    <textarea v-model="indications"
                                        placeholder="Medicamentos, tratamiento, cuidados o seguimiento..."
                                        class="rounded-xl border px-4 py-3 text-sm outline-none resize-none h-36 text-stone-900 border-stone-300"></textarea>
                                </div>

                                <div class="md:col-span-2 flex flex-col gap-1.5">
                                    <label class="text-xs font-semibold uppercase tracking-wider text-stone-500">
                                        Observaciones generales
                                    </label>
                                    <textarea v-model="observations"
                                        placeholder="Agrega notas generales, signos vitales u observaciones complementarias..."
                                        class="rounded-xl border px-4 py-3 text-sm outline-none resize-none h-28 text-stone-900 border-stone-300"></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- ── Banner de errores ──────────────────────── -->
                        <div v-if="hasErrors" class="rounded-xl px-4 py-3 flex items-center gap-3 bg-[#fde8e4]">
                            <AppIcon name="Bell" :size="16" color="#9b3525" />
                            <p class="text-sm font-medium text-[#9b3525]">
                                {{ errorMessage }}
                            </p>
                        </div>

                        <!-- ── Footer ─────────────────────────────────── -->
                        <div class="flex items-center justify-end gap-3 pt-2">
                            <button @click="cancel"
                                class="rounded-xl font-semibold transition-all active:scale-[0.97] select-none cursor-pointer inline-flex items-center justify-center gap-2 text-olive600 bg-transparent px-6 py-3">
                                Cancelar
                            </button>
                            <button @click="submitAppointment" :disabled="!isValid || saving"
                                class="rounded-xl font-semibold transition-all active:scale-[0.97] select-none inline-flex items-center justify-center gap-2 px-8 py-3.5 text-base bg-olive600 text-white disabled:opacity-40 disabled:cursor-not-allowed">
                                {{ saving ? 'Guardando...' : 'Terminar registro' }}
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        </main>
    </div>
</template>