<script setup>
    import { computed } from 'vue';
    import { usePage} from '@inertiajs/vue3';
    
    import AppIcon from '../../components/AppIcon.vue';

    const emit = defineEmits(['create-user']);
    const page = usePage();
    const usuario = computed(() => page.props.auth.user);
    const rolId = computed(() => usuario.value?.role?.id);

    const text = {
        1: {
            title: 'Gestión de usuarios',
            subtitle: 'Administra cuentas, roles y asociaciones escolares.',
            button: 'Nuevo usuario',
        },
        2: {
            title: 'Gestión de personal médico',
            subtitle: 'Administra altas, bajas y consulta la carga de trabajo del departamento.',
            button: 'Nuevo médico',
        },
        3: {
            title: 'Gestión de personal',
            subtitle: 'Administra altas, bajas y asociaciones del equipo de psicología.',
            button: 'Nuevo psicólogo',
        },
    }


    const titles = computed(() => text[rolId.value] ?? {});
</script>

<template>
    <div>
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl sm:text-3xl font-semibold text-stone-900">{{titles.title}}</h1>
                <p class="text-base mt-1.5 text-stone-500">{{titles.subtitle}}</p>
            </div>
            <button @click="emit('create-user')"
                class="bg-oliveKanvu text-white flex justify-center items-center gap-2 px-6 sm:px-8 py-3 rounded-xl sm:rounded-xl shadow-sm hover:opacity-95 transition-opacity w-full md:w-auto">
            <AppIcon name="plus" :size="18" color="white" />
            {{titles.button}}
            </button>
        </div>
    </div>
</template>