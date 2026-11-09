<script setup>
    import { computed } from 'vue';
    import { usePage } from '@inertiajs/vue3';
    import AppIcon from '../../components/AppIcon.vue';

    const page = usePage();
    const usuario = computed(() => page.props.auth.user);
    const rolId = computed(() => usuario.value?.role?.id);

    // ── Admin ─────────────────────────────────────────────
    const informUsers = computed(() => page.props.usersInformation ?? []);
    const hasUsers = computed(() => informUsers.value.length > 0);
    const totalUsers = computed(() => informUsers.value.length);

    const activeUsers = computed(() =>
        informUsers.value.filter(u => Number(u.status_id) === 1).length
    );
    const suspendUsers = computed(() =>
        informUsers.value.filter(u => Number(u.status_id) === 2).length
    );
    const studentUsers = computed(() =>
        informUsers.value.filter(
            u => u.id_rol === 6 && ![2, 3].includes(u.status_id)
        ).length
    );

    // ── Jefe médico (rol 2) ───────────────────────────────
    const informUsersM = computed(() => page.props.usersInformationBossM ?? []);
    const activeUsersM = computed(() =>
        informUsersM.value.filter(u => Number(u.status_id) === 1).length
    );

    // ── Jefe psicólogo (rol 3) ────────────────────────────
    const informUsersP = computed(() => page.props.usersInformationBossP ?? []);
    const activeUsersP = computed(() =>
        informUsersP.value.filter(u => Number(u.status_id) === 1).length
    );

    // ── Enfermero (rol 4) ─────────────────────────────────
    const usersInformationNurse = computed(() => page.props.usersInformationN ?? []);

    const toLocalYMD = (date) => {
        const yyyy = date.getFullYear()
        const mm = String(date.getMonth() + 1).padStart(2, '0')
        const dd = String(date.getDate()).padStart(2, '0')
        return `${yyyy}-${mm}-${dd}`
    }

    const today = computed(() => toLocalYMD(new Date()))

    const allUsersNurseTotal = computed(() => usersInformationNurse.value.length)

    const appointmentsToday = computed(() =>
        usersInformationNurse.value.filter(
            a => a.consultation_date?.slice(0, 10) === today.value
        )
    )


    // ── Cards por rol ─────────────────────────────────────
    const cards = computed(() => {
        const rol = rolId.value
        if (!rol) return []

        const map = {
            1: [
                { number: totalUsers.value, icon: 'users', subtitle: 'Usuarios Registrados' },
                { number: activeUsers.value, icon: 'check', subtitle: 'Cuentas Activas' },
                { number: suspendUsers.value, icon: 'lock', subtitle: 'Cuenta Suspendida' },
                { number: studentUsers.value, icon: 'graduationCap', subtitle: 'Estudiantes Vigentes' },
            ],
            2: [
                { number: activeUsersM.value, icon: 'users', subtitle: 'Personal Activo' },
                { number: 105, icon: 'calendar', subtitle: 'Consultas de esta semana' },
                { number: '95%', icon: 'check', subtitle: 'Asistencia promedio' },
                { number: 11, icon: 'fileText', subtitle: 'Registros recientes' },
            ],
            3: [
                { number: activeUsersP.value, icon: 'users', subtitle: 'Psicólogos activos' },
                { number: 85, icon: 'calendar', subtitle: 'Citas esta semana' },
                { number: '93%', icon: 'check', subtitle: 'Asistencia promedio' },
                { number: 4, icon: 'fileText', subtitle: 'Sesiones grupales' },
            ],
            4: [
                { number: allUsersNurseTotal.value, icon: 'calendar', subtitle: 'Citas totales' },
                { number: appointmentsToday.value.length, icon: 'check', subtitle: 'Citas del día' }
            ],
            5: [
                { number: 6, icon: 'calendar', subtitle: 'Citas totales' },
                { number: 5, icon: 'check', subtitle: 'Asistencias' },
                { number: 10, icon: 'pending', subtitle: 'Pendientes de agendar' },
                { number: 2, icon: 'clock', subtitle: 'Próximas' },
            ],
        }

        return map[rol] ?? []
    })
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
                    {{ card.number ?? 0 }}
                </p>

                <p class="text-sm font-medium mt-0.5 text-stone500">
                    {{ card.subtitle }}
                </p>
            </div>

        </div>
    </div>
</template>