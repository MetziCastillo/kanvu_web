<script setup>
    import { computed, ref, watch } from 'vue';
    import { usePage, router } from '@inertiajs/vue3';

    import AppIcon from '../../components/AppIcon.vue';
    import Navbar from '../../components/navbar.vue';
    import InputLabel from '../../components/Input_labels.vue';
    import Card from '../../components/Cards.vue';

    const props = defineProps({
        roles: { type: Array, required: true },
        careers: { type: Array, required: true },
        department: { type: Array, required: true },
        modality: { type: Array, required: true },
        area: { type: Array, required: true }
    });

    const page = usePage();
    const usuario = computed(() => page.props.auth.user);
    const rolId = computed(() => usuario.value?.role?.id);

    // ── Búsqueda por matrícula ──────────────────────────────
    const matricula = ref('');
    const searchStatus = ref('idle');
    const manualMode = ref(false);

    // ── Datos del estudiante ────────────────────────────────
    const studentId = ref(null);
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
    const type = ref(null);          // área seleccionada (psicólogo)
    const modalityId = ref(null);    // modalidad seleccionada (psicólogo)

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

    // ── Validación de horas (front) ─────────────────────────
    const timeRangeError = computed(() => {
        if (!startTime.value || !endTime.value) return null;
        if (endTime.value <= startTime.value) {
            return 'La hora de término debe ser posterior a la hora de inicio.';
        }
        return null;
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
        studentId.value = null;
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
        studentId.value = null;
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
        if (timeRangeError.value) return false; // fin > inicio
        if (!discomfort.value.trim()) return false;

        // Psicólogo: requiere área y modalidad
        if (rolId.value === 5) {
            if (!type.value?.id) return false;
            if (!modalityId.value) return false;
        }

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
            student_id: studentId.value,
            student_name: studentName.value.trim(),
            student_last_name: studentLastName.value.trim(),
            career_id: manualMode.value
                ? Number(selectedCareer.value)
                : Number(studentCareerId.value),
            group_id: manualMode.value
                ? Number(selectedGroup.value)
                : Number(studentGroupId.value),
            appointment_date: appointmentDate.value,
            start_time: startTime.value,
            end_time: endTime.value,
            discomfort: discomfort.value.trim(),
            indications: indications.value.trim(),
            observations: observations.value.trim(),
        };

        // Solo el psicólogo envía área y modalidad
        if (rolId.value === 5) {
            payload.area_id = type.value?.id ?? null;
            payload.modality_id = modalityId.value ?? null;
        }

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

            // Redirigir según el rol
            if (rolId.value === 5) {
                router.visit('/dashboard/psychologist');
            } else {
                router.visit('/dashboard/nurse');
            }
        } catch (e) {
            console.error(e);
            errors.value = { general: ['Error de conexión al guardar la cita.'] };
        } finally {
            saving.value = false;
        }
    };

    const titles = computed(() => {
        const rol = rolId.value;
        if (!rol) return [];

        const map = {
            4: {
                title: 'Registrar cita',
                subtitle: 'Busca al estudiante y completa los datos de la consulta médica',
                buttonTitle: 'Terminar registro',
                detail1: 'Malestar / Motivo de la consulta',
                subdetail1: 'Describe el malestar o motivo de la consulta...',
                subdetail2: 'Medicamentos, tratamientos, cuidados o seguimiento...',
                subdetail3: 'Agrega notas generales, signos vitales u observaciones complementarias...'
            },
            5: {
                title: 'Nueva cita psicología',
                subtitle: 'Registra al paciente, el servicio y la información de la consulta',
                buttonTitle: 'Registrar cita',
                detail1: 'Motivo de consulta',
                subdetail1: 'Describe el motivo principal...',
                subdetail2: 'Plan inicial, recomendaciones o seguimiento...',
                subdetail3: 'Contexto adicional u observaciones relevantes...'
            },
        };

        return map[rol] ?? [];
    });
</script>

<template>
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
                        <h1 class="text-2xl font-semibold text-stone-900"> {{ titles.title }} </h1>
                        <p class="text-sm text-stone-500">
                            {{ titles.subtitle }}
                        </p>
                    </div>
                </div>

                <div class="p-8 bg-white rounded-2xl hover:shadow-sm transition-shadow border border-stone-100">
                    <div class="flex flex-col gap-8">

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

                        <!-- ── Servicio y modalidad (psicólogo) ──────── -->
                        <div v-if="rolId === 5">
                            <p class="text-xs font-bold uppercase tracking-wider mb-4 text-stone-400">
                                Servicio y modalidad
                            </p>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <button v-for="option in area" :key="option.id" @click="type = option"
                                    class="rounded-2xl p-5 text-left border flex items-center gap-4 transition-colors"
                                    :style="type?.id === option.id ? { background: 'var(--color-olive50)', borderColor: 'var(--color-olive500)' } : { borderColor: 'var(--color-stone100)' }">

                                    <div class="w-11 h-11 rounded-xl flex items-center justify-center"
                                        :style="{ background: type?.id === option.id ? 'var(--color-olive200)' : 'var(--color-stone100)' }">
                                        <AppIcon v-if="option.id === 1" name="leaf" size="20"
                                            color="var(--color-olive700)" />
                                        <AppIcon v-else name="graduationCap" size="20" color="var(--color-olive700)" />
                                    </div>

                                    <div class="flex-1">
                                        <p class="font-semibold" :style="{ color: 'var(--color-stone900)' }">
                                            {{ option.name }}
                                        </p>
                                        <p class="text-xs mt-1" :style="{ color: 'var(--color-stone500)' }">
                                            {{ option.id === 1
                                            ? 'Acompañamiento emocional y salud mental'
                                            : 'Aprendizaje, hábitos y orientación académica' }}
                                        </p>
                                    </div>

                                    <AppIcon v-if="type?.id === option.id" name="check" size="18"
                                        color="var(--color-olive600)" />
                                </button>
                            </div>
                            <p v-if="errors.area_id" class="text-xs text-red-600 mb-4">
                                {{ errors.area_id[0] }}
                            </p>

                            <!-- Modalidad -->
                            <div class="flex flex-col gap-1.5 max-w-sm">
                                <label class="text-xs font-semibold uppercase tracking-wider text-stone-500">
                                    Modalidad
                                </label>
                                <select v-model="modalityId" name="modality_id"
                                    class="w-full rounded-xl border bg-white text-sm outline-none py-3 px-4 border-stone-300 text-stone-900">
                                    <option :value="null" disabled>Selecciona una modalidad</option>
                                    <option v-for="m in modality" :key="m.id" :value="m.id">
                                        {{ m.name }}
                                    </option>
                                </select>
                                <p v-if="errors.modality_id" class="text-xs text-red-600">
                                    {{ errors.modality_id[0] }}
                                </p>
                            </div>
                        </div>

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

                            <!-- Error de rango de horas (front) -->
                            <p v-if="timeRangeError" class="text-xs text-red-600 mb-4">
                                {{ timeRangeError }}
                            </p>
                            <p v-if="errors.start_time" class="text-xs text-red-600 mb-4">
                                {{ errors.start_time[0] }}
                            </p>
                            <p v-if="errors.end_time" class="text-xs text-red-600 mb-4">
                                {{ errors.end_time[0] }}
                            </p>
                            <p v-if="errors.appointment_date" class="text-xs text-red-600 mb-4">
                                {{ errors.appointment_date[0] }}
                            </p>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="flex flex-col gap-1.5">
                                    <label class="text-xs font-semibold uppercase tracking-wider text-stone-500">
                                        {{ titles.detail1 }}
                                    </label>
                                    <textarea v-model="discomfort" :placeholder="titles.subdetail1"
                                        class="rounded-xl border px-4 py-3 text-sm outline-none resize-none h-36 text-stone-900 border-stone-300"></textarea>
                                    <p v-if="errors.discomfort" class="text-xs text-red-600">
                                        {{ errors.discomfort[0] }}
                                    </p>
                                </div>

                                <div class="flex flex-col gap-1.5">
                                    <label class="text-xs font-semibold uppercase tracking-wider text-stone-500">
                                        Indicaciones
                                    </label>
                                    <textarea v-model="indications" :placeholder="titles.subdetail2"
                                        class="rounded-xl border px-4 py-3 text-sm outline-none resize-none h-36 text-stone-900 border-stone-300"></textarea>
                                </div>

                                <div class="md:col-span-2 flex flex-col gap-1.5">
                                    <label class="text-xs font-semibold uppercase tracking-wider text-stone-500">
                                        Observaciones generales
                                    </label>
                                    <textarea v-model="observations" :placeholder="titles.subdetail3"
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
                                {{ saving ? 'Guardando...' : titles.buttonTitle }}
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        </main>
    </div>
</template>