<script setup>
    import { computed } from 'vue';
    import { usePage } from '@inertiajs/vue3';
    import AppIcon from '../../components/AppIcon.vue';

    const page = usePage();
    const usuario = computed(() => page.props.auth.user);
    const rolId = computed(() => usuario.value?.role?.id);

    const informUsers = computed(() => page.props.usersInformation);

    const activeUsers = computed(() => informUsers.value.filter(u => Number(u.status_id) === 1).length);
    const suspendUsers = computed(() => informUsers.value.filter(u => Number(u.status_id) === 2).length);
    const studentUsers = computed(() =>
        informUsers.value.filter(u => u.id_rol === 6 && ![2, 3].includes(u.status_id)).length
    );

    const usuariosTotales = informUsers.value.length;


    const cardsRole = {
        1: [
            {
                number: usuariosTotales,
                icon: 'users',
                subtitle: 'Usuarios Registrados',
            },
            {
                number: activeUsers,
                icon: 'check',
                subtitle: 'Cuentas Activas',
            },
            {
                number: suspendUsers,
                icon: 'lock',
                subtitle: 'Cuenta Suspendida',
            },
            {
                number: studentUsers,
                icon: 'graduationCap',
                subtitle: 'Estudiantes Vigentes',
            },
        ],
        5: [
            {
                number: '6',
                icon: 'calendar',
                subtitle: 'Citas totales',
            },
            {
                number: '5',
                icon: 'check',
                subtitle: 'Asistencias',
            },
            {
                number: '10',
                icon: 'pending',
                subtitle: 'Pendientes de agendar',
            },
            {
                number: '2',
                icon: 'clock',
                subtitle: 'Próximas',
            },
        ],
    };

    const cards = computed(() => cardsRole[rolId.value] ?? []);

</script>

<template>
    <div v-for="card in cards" :key="card.subtitle"
        class="p-5 bg-white rounded-2xl hover:shadow-sm transition-shadow border border-stone-100">
        <div class="flex items-center gap-4">

            <div class="w-14 h-14 rounded-2xl flex items-center justify-center flex-shrink-0 bg-[#F5F8EE]">
                <AppIcon :name="card.icon" :size="18" color="var(--color-olive700)" />
            </div>

            <div>
                <p class="text-3xl font-semibold text-stone900">
                    {{ card.number }}
                </p>

                <p class="text-sm font-medium mt-0.5 text-stone500">
                    {{ card.subtitle }}
                </p>
            </div>

        </div>

    </div>

</template>