<script setup>
    import { computed } from 'vue';
    import { usePage, Link } from '@inertiajs/vue3';

    import AppIcon from '../../components/AppIcon.vue';
    import Card from './Card.vue';

    const page = usePage();
    const usuario = computed(() => page.props.auth.user);
    const rolId = computed(() => usuario.value?.role?.id);

    const text = {
        1: {
            title: 'Panel de administración',
            subtitle: 'Supervisa cuentas, estructura escolar y configuración general de Kanvu.',
            button: 'Crear usuario',
            buttonRoute: '/user_management/Dashboard_um',
        },
        5: {
            title: '',
            button: 'Nueva Cita',
            buttonRoute: ''
        }
    }


    const titles = computed(() => text[rolId.value] ?? {});
</script>

<template>
    <div>
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
            <div v-if="rolId === 5">
                <p class="text-base font-medium mb-1 text-stone-500">Hola!</p>
                <h1 class="font-display text-2xl sm:text-3xl font-semibold text-stone-900">Sofía Ramírez</h1>
            </div>
            <div v-else>
                <h1 class="font-display text-2xl sm:text-3xl font-semibold text-stone-900">{{titles.title}}</h1>
                <p class="text-base mt-1.5 text-stone-500">{{titles.subtitle}}</p>
            </div>
            <Link :href="titles.buttonRoute"
                class="bg-olive700 text-white flex justify-center items-center gap-2 px-6 sm:px-8 py-3 rounded-xl sm:rounded-xl shadow-sm hover:opacity-95 transition-opacity w-full md:w-auto">
            <AppIcon name="plus" :size="18" color="white" />
            {{titles.button}}
            </Link>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 mb-8">
            <Card />
        </div>
    </div>
</template>