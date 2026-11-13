<script setup>
    import { computed, ref, onMounted, onUnmounted } from 'vue';
    import { usePage } from '@inertiajs/vue3';

    import AppIcon from '@/components/AppIcon.vue';
    import MedicalStaffCard from '@/components/dashboard/Medical_staff_card.vue';
    import { getAvailability } from '@/utils/availability';
    import { isUnavailableStatus, statusLabel } from '@/utils/status';

    const page = usePage();
    const employees = computed(() => page.props.employees ?? []);


    const now = ref(new Date());
    let timer = null;
    onMounted(() => { timer = setInterval(() => { now.value = new Date(); }, 60_000); });
    onUnmounted(() => clearInterval(timer));

    const employeesWithAvailability = computed(() =>
        employees.value.map(emp => {

            if (isUnavailableStatus(emp)) {
                return {
                    ...emp,
                    availability: {
                        label: 'Sin disponibilidad',
                        color: 'inactive',
                        next: null,
                        nextLabel: statusLabel(emp.status_id),
                    },
                };
            }

            return {
                ...emp,
                availability: getAvailability(emp.schedules ?? [], now.value),
            };
        })
    );
</script>

<template>
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-3 mb-4">
        <div>
            <p class="text-2xl font-semibold text-stone-900">Equipo médico</p>
            <p class="text-sm mt-1 text-stone-500">
                Selecciona una tarjeta para revisar horario, estadísticas y registros de consulta.
            </p>
        </div>

        <button
            class="text-sm font-semibold flex items-center gap-1.5 text-olive-600 rounded-xl transition-all active:scale-[0.97] select-none cursor-pointer">
            Ver todos los registros
            <AppIcon name="chevronRight" size="15" color="var(--color-olive-600)" />
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
        <MedicalStaffCard v-for="emp in employeesWithAvailability" :key="emp.employee_id" :user="emp" />
    </div>
</template>