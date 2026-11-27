<script setup>
    import { computed } from 'vue';
    import { usePage, Link } from '@inertiajs/vue3';
    import AppIcon from '../../components/AppIcon.vue';

    const page = usePage();
    const usuario = computed(() => page.props.auth.user);
    const rolId = computed(() => usuario.value?.role?.id);

    const access = {
        1: [
            {
                title: 'Gestionar usuarios',
                subtitle: 'Roles, estados y asignaciones',
                screen: '',
                icon: 'users'
            },
            {
                title: 'Catálogos escolares',
                subtitle: 'Carreras, grupos y periodos',
                screen: '',
                icon: 'boocksOpen'
            },
            {
                title: 'Mi cuenta',
                subtitle: 'Datos y seguridad',
                screen: '',
                icon: 'user',
            }
        ],
        5: [
            {
                title: 'Registrar nueva cita',
                subtitle: 'Asigna un paciente y horario',
                screen: '/appointments/Medical',
                icon: 'plus'
            },
            {
                title: 'Consultar historial',
                subtitle: 'Revisa notas de sesiones anteriores',
                screen: '',
                icon: 'history'
            },
            {
                title: 'Ver toda mi agenda',
                subtitle: 'Próximas y anteriores',
                screen: '',
                icon: 'user',
            }
        ]
    }

    const accesses = computed(() => access[rolId.value] ?? []);

</script>

<template>
    <div class="bg-white rounded-2xl hover:shadow-sm transition-shadow border border-stone-100 p-5">
        <p class="font-semibold mb-4 text-stone900">Accesos Rápidos</p>

        <div class="flex flex-col gap-2">
            <Link v-for="ac in accesses" :key="ac.title" :href="ac.screen"
                class="flex items-center gap-3 p-3 rounded-xl text-left hover:bg-stone-50 transition-colors">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-olive50">
                <AppIcon :name="ac.icon" :size="18" color="var(--color-olive700)" />
            </div>
            <div class="flex-1">
                <p class="text-sm font-semibold text-stone900">{{ ac.title }}</p>
                <p class="text-xs mt-0.5 text-stone400">{{ ac.subtitle }}</p>
            </div>
            <AppIcon name="chevronRight" :size="15" color="var(--color-stone300)" />
            </Link>
        </div>
    </div>
</template>