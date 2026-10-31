<script setup>
    import { computed, ref, watch } from 'vue';
    import { usePage } from '@inertiajs/vue3';

    import AppIcon from '../../components/AppIcon.vue';
    import InputLabel from '../../components/Input_labels.vue';

    const props = defineProps({
        roles: { type: Array, required: true },
        careers: { type: Array, required: true },
        departments: { type: Array, required: true },
    });

    const page = usePage();
    const usuario = computed(() => page.props.auth.user);
    const rolId = computed(() => usuario.value?.role?.id);
    const emit = defineEmits(['create-user', 'user-created']);

    const text_name = ref('');
    const text_lastName = ref('');
    const text_matricula = ref('');
    const text_email = ref('');
    const selectedRole = ref('');
    const selectedCareer = ref('');
    const selectedGroup = ref('');
    const text_nomina = ref('');
    const selectedDepartment = ref('');

    const groups = ref([]);
    const loadingGroups = ref(false);

    // --- Estado para guardado ---
    const saving = ref(false);
    const errors = ref({});

    const titleRoles = {
        1: 'Administrador',
        2: 'Jefe de Departamento Médico',
        3: 'Jefe de Departamento de Psicología',
        4: 'Enfermero',
        5: 'Psicólogo',
        6: 'Estudiante',
    };

    const Viewoptions = {
        1: {
            CreateName: true,
            CreateLastName: true,
            CreateEmail: true,
            SelectRol: true,
            SelectDepartment: true,
            createMatricula: true,
            CreateNomina: true,
        },
        2: {
            CreateName: true,
            CreateLastName: true,
            CreateEmail: true,
            SelectRol: false,
            SelectDepartment: false,
            createMatricula: false,
            CreateNomina: true,
        },
        3: {
            CreateName: true,
            CreateLastName: true,
            CreateEmail: true,
            SelectRol: false,
            SelectDepartment: true,
            createMatricula: false,
            CreateNomina: true,
        }
    };

    const options = computed(() => Viewoptions[rolId.value] ?? {});

    const textsAdmin = {
        1: { advise: 'Los permisos se asignarán automáticamente según el rol.' },
        2: { advise: 'Se creará una cuenta inicial de jefatura con acceso a su departamento.' },
        3: { advise: 'Se creará una cuenta inicial de jefatura con acceso a su departamento.' },
        4: { advise: 'Los permisos se asignarán automáticamente según el rol.' },
        5: { advise: 'Los permisos se asignarán automáticamente según el rol.' },
        6: { advise: 'Los permisos se asignarán automáticamente según el rol.' },
    };

    const textBoss = {
        2: { advise: 'La cuenta se activará de inmediato. El horario podrá configurarse posteriormente.' },
        3: { advise: 'La cuenta se dará de alta como activa. Después podrás asignarle carreras y consultar su horario.' },
    };

    const defaultAdvise = 'Selecciona un rol para ver los detalles de la cuenta.';

    const roleInfo = computed(() => {
        const id = Number(selectedRole.value);
        if (!id) return { advise: defaultAdvise };
        return textsAdmin[id] ?? textBoss[id] ?? { advise: defaultAdvise };
    });

    // Cargar grupos cuando cambia la carrera
    watch(selectedCareer, async (careerId) => {
        selectedGroup.value = '';
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
    });

    watch(selectedRole, (newRole) => {
        if (Number(newRole) !== 6) {
            selectedCareer.value = '';
            selectedGroup.value = '';
            groups.value = [];
        }
    });

    const isValid = computed(() => {
        if (!text_name.value.trim()) return false;
        if (!text_lastName.value.trim()) return false;
        if (!text_email.value.trim()) return false;
        if (!selectedRole.value) return false;

        const roleNum = Number(selectedRole.value);

        if (roleNum === 6) {
            if (!text_matricula.value.trim()) return false;
            if (!selectedCareer.value) return false;
            if (!selectedGroup.value) return false;
        } else {
            if (!text_nomina.value.trim()) return false;
            if (options.value.SelectDepartment && !selectedDepartment.value) return false;
        }

        return true;
    });

    const saveUser = async () => {
        if (!isValid.value || saving.value) return;

        saving.value = true;
        errors.value = {};

        const roleNum = Number(selectedRole.value);

        // Construir payload según el rol
        const payload = {
            name: text_name.value.trim(),
            last_name: text_lastName.value.trim(),
            email: text_email.value.trim(),
            role_id: roleNum,
        };

        if (roleNum === 6) {
            // Estudiante
            payload.matricula = text_matricula.value.trim();
            payload.career_id = Number(selectedCareer.value);
            payload.group_id = Number(selectedGroup.value);
        } else {
            // Staff (admin, jefes, enfermero, psicólogo)
            payload.nomina = text_nomina.value.trim();
            if (options.value.SelectDepartment && selectedDepartment.value) {
                payload.department_id = Number(selectedDepartment.value);
            }
        }

        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            const res = await fetch('/users', {
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
                // Laravel devuelve 422 con { errors: {...} }
                if (res.status === 422 && data.errors) {
                    errors.value = data.errors;
                } else {
                    errors.value = { general: [data.message ?? 'Error al crear el usuario.'] };
                }
                return;
            }

            // Éxito
            emit('user-created', data.user ?? data);
            resetForm();
            emit('create-user'); // cierra el modal (tu evento actual)
        } catch (e) {
            console.error(e);
            errors.value = { general: ['Error de conexión al crear el usuario.'] };
        } finally {
            saving.value = false;
        }
    };

    const resetForm = () => {
        text_name.value = '';
        text_lastName.value = '';
        text_email.value = '';
        text_matricula.value = '';
        text_nomina.value = '';
        selectedRole.value = '';
        selectedCareer.value = '';
        selectedGroup.value = '';
        selectedDepartment.value = '';
        groups.value = [];
        errors.value = {};
    };
</script>

<template>
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl overflow-hidden">
        <div class="flex items-center justify-between px-7 py-5 border-b border-Stone100">
            <div>
                <h2 class="text-2xl font-semibold text-stone900">Crear usuario</h2>
                <p class="text-sm mt-1 text-stone500">
                    Configura sus datos, rol y asociación escolar.
                </p>
            </div>
            <button @click="emit('create-user')"
                class="w-9 h-9 rounded-xl flex items-center justify-center hover:bg-stone-100 text-stone900">
                <AppIcon name="close" size="18" color="var(--color-stone500)" />
            </button>
        </div>

        <div class="p-7 grid grid-cols-1 md:grid-cols-2 gap-5">

            <InputLabel title="Nombre" label="name" placeholder="Nombre" type="text" v-model="text_name" />
            <InputLabel title="Apellidos" label="last_name" placeholder="Apellidos" type="text"
                v-model="text_lastName" />
            <InputLabel title="Correo institucional" label="email" placeholder="Ejemplo: matricula@virtual.utsc.edu.mx"
                type="email" v-model="text_email" />

            <div class="rounded-xl p-4 flex gap-3 bg-olive50">
                <AppIcon name="shield" size="18" color="var(--color-oliveKanvu)" />
                <p class="text-xs leading-relaxed text-olive700">{{ roleInfo.advise }}</p>
            </div>

            <div v-if="options.SelectRol" class="flex flex-col gap-1.5">
                <label class="text-xs font-semibold uppercase tracking-wider text-stone500">Rol</label>
                <select v-model="selectedRole" name="role" id="role"
                    class="w-full rounded-xl border bg-white text-sm outline-none py-3 px-4 border-stone300 text-stone900">
                    <option value="" class="text-stone300" disabled>Selecciona un rol</option>
                    <option v-for="rol in roles" :key="rol.id" :value="rol.id">
                        {{ titleRoles[rol.id] ?? rol.name }}
                    </option>
                </select>
                <p v-if="errors.role_id" class="text-xs text-red-600">{{ errors.role_id[0] }}</p>
            </div>

            <InputLabel v-if="Number(selectedRole) === 6 && options.createMatricula" title="Matrícula" label="matricula"
                placeholder="00000" type="text" v-model="text_matricula" />
            <InputLabel v-if="Number(selectedRole) !== 6 && Number(selectedRole) !== ''" title="Nómina" label="nomina"
                placeholder="00000" type="text" v-model="text_nomina" />


            <div v-if="Number(selectedRole) === 6" class="flex flex-col gap-1.5">
                <label class="text-xs font-semibold uppercase tracking-wider text-stone500">Carrera</label>
                <select v-model="selectedCareer" name="career_id" id="career_id"
                    class="w-full rounded-xl border bg-white text-sm outline-none py-3 px-4 border-stone300 text-stone900">
                    <option value="" class="text-stone300" disabled>Selecciona una carrera</option>
                    <option v-for="career in careers" :key="career.id" :value="career.id">
                        {{ career.name }}
                    </option>
                </select>
                <p v-if="errors.career_id" class="text-xs text-red-600">{{ errors.career_id[0] }}</p>
            </div>

            <div v-if="Number(selectedRole) === 6 && selectedCareer" class="flex flex-col gap-1.5">
                <label class="text-xs font-semibold uppercase tracking-wider text-stone500">Grupo</label>
                <select v-model="selectedGroup" name="group_id" :disabled="loadingGroups"
                    class="w-full rounded-xl border bg-white text-sm outline-none py-3 px-4 border-stone300 text-stone900 disabled:opacity-50">
                    <option value="" class="text-stone300" disabled>
                        {{ loadingGroups ? 'Cargando...' : 'Selecciona un grupo' }}
                    </option>
                    <option v-for="g in groups" :key="g.id" :value="g.id">
                        {{ g.grade_name }} — {{ g.group_name }}
                    </option>
                </select>
                <p v-if="errors.group_id" class="text-xs text-red-600">{{ errors.group_id[0] }}</p>
            </div>

            <div v-if="Number(selectedRole) !== 6 && Number(selectedRole) !==''  && options.SelectDepartment"
                class="flex flex-col gap-1.5">
                <label class="text-xs font-semibold uppercase tracking-wider text-stone500">Departamento</label>
                <select v-model="selectedDepartment" name="department_id"
                    class="w-full rounded-xl border bg-white text-sm outline-none py-3 px-4 border-stone300 text-stone900 disabled:opacity-50">
                    <option value="" disabled>
                        Selecciona un departamento
                    </option>
                    <option v-for="dep in departments" :key="dep.id" :value="dep.id">
                        {{dep.name}}
                    </option>
                </select>
                <p v-if="errors.department_id" class="text-xs text-red-600">{{ errors.department_id[0] }}</p>
            </div>

            <!-- Errores generales -->
            <div v-if="errors.general" class="md:col-span-2 rounded-xl p-3 bg-red-50 border border-red-200">
                <p class="text-xs text-red-700">{{ errors.general[0] }}</p>
            </div>
            <div v-if="errors.email" class="md:col-span-2 rounded-xl p-3 bg-red-50 border border-red-200">
                <p class="text-xs text-red-700">{{ errors.email[0] }}</p>
            </div>

        </div>
        <div class="px-7 py-5 border-t flex justify-end gap-3 border border-Stone100 bg-Stone100">
            <button type="button" @click="emit('create-user')"
                class="rounded-xl font-semibold transition-all active:scale-[0.97] select-none cursor-pointer inline-flex items-center justify-center gap-2 px-6 py-3 text-sm border-[1.5px] border-olive300 text-olive700 bg-transparent">
                Cancelar
            </button>
            <button type="button" @click="saveUser" :disabled="!isValid || saving"
                class="rounded-xl font-semibold transition-all active:scale-[0.97] select-none inline-flex items-center justify-center gap-2 px-6 py-3 text-sm bg-olive600 text-white disabled:opacity-40 disabled:cursor-not-allowed">
                {{ saving ? 'Creando...' : 'Crear cuenta' }}
            </button>
        </div>
    </div>
</template>