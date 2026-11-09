<script setup>
    import { computed, ref } from 'vue';
    import { usePage } from '@inertiajs/vue3';

    import InputLabel from '../../components/Input_labels.vue';
    import AvatarInitials from '../../components/Avatar_initials.vue';
    import AdminStatus from './Admin_status.vue';

    const emit = defineEmits(['edit', 'updateStatus']);

    const page = usePage();
    const usuario = computed(() => page.props.auth.user);
    const rolId = computed(() => usuario.value?.role?.id);

    const props = defineProps({
        allUsers: { type: Array, required: true },
        processingUserId: { type: [Number, String], default: null },
    });

    const search_value = ref('');
    const selected = ref('Todas');

    const opcionesActivaBaja = [
        { title: 'Todas' },
        { title: 'Activa' },
        { title: 'Baja' }
    ];

    const button = {
        1: [{ title: 'Todas' }, { title: 'Activa' }, { title: 'Suspendida' }, { title: 'Baja' }],
        2: opcionesActivaBaja,
        3: opcionesActivaBaja
    };

    const tableTitles = [
        { title: 'Usuario' },
        { title: 'Rol' },
        { title: 'Asignación' },
        { title: 'Estado' },
        { title: 'Acciones' }
    ];

    const titleRoles = {
        1: 'Administrador',
        2: 'Jefe de Departamento Médico',
        3: 'Jefe de Departamento de Psicología',
        4: 'Enfermero',
        5: 'Psicólogo',
        6: 'Estudiante',
    };

    const buttonOptions = computed(() => button[rolId.value] ?? []);

    const btnStyles = {
        edit: 'bg-olive50 text-olive700',
        suspend: 'bg-sand text-[#7a6a30]',
        delete: 'bg-[#fde8e4] text-[#9b3525]'
    };

    const filteredUsers = computed(() => {
        const term = search_value.value.trim().toLowerCase();
        return props.allUsers.filter((user) => {
            const matchesSearch =
                !term ||
                `${user.name} ${user.last_name} ${user.email} ${titleRoles[user.id_rol]} ${user.career_name}`
                    .toLowerCase()
                    .includes(term);

            const matchesStatus =
                selected.value === 'Todas' || user.status_name === selected.value;

            return matchesSearch && matchesStatus;
        });
    });

    const handleEdit = (user) => emit('edit', user);

    const handleToggleSuspension = (user) => {
        const newStatus = user.status_name === 'Suspendida' ? 'Activa' : 'Suspendida';
        emit('updateStatus', user.user_id, newStatus);   // ← user_id (como expone AllUsers)
    };

    const handleDelete = (user) => emit('updateStatus', user.user_id, 'Baja');  // ← user_id
</script>

<template>
    <div class="p-0 bg-white rounded-2xl hover:shadow-sm transition-shadow border border-stone-100">
        <div class="p-5 border-b flex flex-col md:flex-row gap-3 md:items-center border-stone-100">
            <div class="flex-1">
                <InputLabel label="search" placeholder="Buscar por nombre, correo o rol..." type="text"
                    v-model="search_value" />
            </div>
            <div class="flex gap-2 overflow-x-auto">
                <button v-for="btnO in buttonOptions" :key="btnO.title" type="button" @click="selected = btnO.title"
                    class="px-4 py-2.5 rounded-xl text-xs font-semibold border whitespace-nowrap"
                    :class="selected === btnO.title ? 'border-olive600 text-white bg-olive600' : 'border-stone100 text-stone500 bg-white'">
                    {{ btnO.title }}
                </button>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-olive50">
                        <th v-for="t in tableTitles" :key="t.title"
                            class="px-6 py-3.5 text-xs font-bold uppercase tracking-wider text-stone500">{{ t.title }}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="user in filteredUsers" :key="user.user_id"
                        class="border-t hover:bg-stone-50 transition-colors border-stone100">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <AvatarInitials :name="`${user.name} ${user.last_name}`" />
                                <div>
                                    <p class="text-sm font-semibold text-stone900">{{ user.name }} {{ user.last_name }}
                                    </p>
                                    <p class="text-xs mt-0.5 text-stone400">{{ user.email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-stone700">{{ titleRoles[user.id_rol] ??
                            user.rol_name }}</td>
                        <td class="px-6 py-4 text-sm text-stone-500">
                            {{
                            user.career_name
                            ? `${user.career_name} · ${user.grade_name}-${user.group_name}`
                            : 'No aplica'
                            }}
                        </td>
                        <td class="px-6 py-4">
                            <AdminStatus :status="user.status_name" />
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <button type="button" @click="handleEdit(user)"
                                    class="px-3 py-2 rounded-lg text-xs font-semibold cursor-pointer"
                                    :class="btnStyles.edit">Editar</button>

                                <template v-if="user.status_name !== 'Baja'">
                                    <button type="button" @click="handleToggleSuspension(user)"
                                        :disabled="processingUserId === user.user_id"
                                        class="px-3 py-2 rounded-lg text-xs font-semibold cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed"
                                        :class="btnStyles.suspend">
                                        {{ user.status_name === 'Suspendida' ? 'Reactivar' : 'Suspender' }}
                                    </button>

                                    <button type="button" @click="handleDelete(user)"
                                        :disabled="processingUserId === user.user_id"
                                        class="px-3 py-2 rounded-lg text-xs font-semibold cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed"
                                        :class="btnStyles.delete">
                                        Dar de baja
                                    </button>
                                </template>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            <p v-if="filteredUsers.length === 0" class="text-center py-12 text-sm text-stone500">
                No se encontraron usuarios con ese criterio.
            </p>
        </div>
    </div>
</template>