<script setup>
    import { computed } from 'vue';

    import Card from '@/components/Cards.vue';
    import AppIcon from '@/components/AppIcon.vue';
    import AvatarInitials from '@/components/Avatar_initials.vue';
    import StatusBadge from '@/components/dashboard/Admin_status.vue';
    import { USER_STATUS } from '@/utils/status';

    const props = defineProps({
        user: { type: Object, required: true },
    });

    const isInactiveStatus = computed(() => {
        const id = Number(props.user.status_id);
        return id === USER_STATUS.SUSPENDED || id === USER_STATUS.INACTIVE;
    });

    const isActiveStatus = computed(() => Number(props.user.status_id) === USER_STATUS.ACTIVE);

    const cardClasses = computed(() => ({
        'opacity-60 grayscale': isInactiveStatus.value,
    }));

    const topBarClasses = computed(() => ({
        'bg-olive-500': isActiveStatus.value,
        'bg-stone-300': !isActiveStatus.value,
    }));

    const availabilityBg = computed(() => ({
        'bg-green-50': props.user.availability?.color === 'green',
        'bg-amber-50': props.user.availability?.color === 'amber',
        'bg-blue-50': props.user.availability?.color === 'blue',
        'bg-stone-100': props.user.availability?.color === 'gray'
            || props.user.availability?.color === 'inactive'
            || !props.user.availability,
    }));

    const availabilityText = computed(() => ({
        'text-green-700': props.user.availability?.color === 'green',
        'text-amber-700': props.user.availability?.color === 'amber',
        'text-blue-700': props.user.availability?.color === 'blue',
        'text-stone-500': props.user.availability?.color === 'gray'
            || props.user.availability?.color === 'inactive'
            || !props.user.availability,
    }));

    const availabilityIconColor = computed(() => {
        switch (props.user.availability?.color) {
            case 'green': return 'var(--color-green-600, #16a34a)';
            case 'amber': return 'var(--color-amber-600, #d97706)';
            case 'blue': return 'var(--color-blue-600,  #2563eb)';
            default: return 'var(--color-stone-400, #a8a29e)';
        }
    });
</script>

<template>
    <Card class="overflow-hidden" :class="cardClasses" padding="p-0"
        @click="console.log('id usuario: ' + user.employee_id)">

        <div class="h-1.5" :class="topBarClasses"></div>

        <div class="p-5">
            <div class="flex items-start gap-4">
                <AvatarInitials :name="`${user.first_name} ${user.last_name}`" size="lg" />
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0">
                            <p class="font-semibold text-base truncate text-stone-900">
                                {{ user.first_name }} {{ user.last_name }}
                            </p>
                        </div>
                        <StatusBadge :status="user.status_name" />
                    </div>
                </div>
            </div>

            <div v-if="!isInactiveStatus"
                class="mt-5 pt-4 border-t grid grid-cols-2 gap-3 border-stone-100 min-h-[72px]">
                <div>
                    <p class="text-2xl font-semibold text-stone-900">{{ user.total_appointments }}</p>
                    <p class="text-xs text-stone-500">Consultas totales</p>
                </div>
                <div>
                    <p class="text-2xl font-semibold text-olive-700">{{ user.appointments_today }}</p>
                    <p class="text-xs text-stone-500">Consultas de hoy</p>
                </div>
            </div>

            <div v-else class="mt-5 pt-4 border-t border-stone-100 min-h-[72px] flex items-center">
                <p class="text-xs text-stone-400">
                    Empleado {{ user.status_name?.toLowerCase() ?? 'inactivo' }} — sin estadísticas
                </p>
            </div>

            <div class="mt-4 rounded-xl px-3.5 py-3 flex items-center gap-2.5" :class="availabilityBg">
                <AppIcon name="clock" size="16" :color="availabilityIconColor" />
                <p class="text-xs flex-1 truncate" :class="availabilityText">
                    {{ user.availability?.label ?? 'Sin disponibilidad' }}
                    <span v-if="user.availability?.next" class="opacity-80">
                        · {{ user.availability.nextLabel }} {{ user.availability.next }}
                    </span>
                </p>
                <AppIcon name="chevronRight" size="14" color="var(--color-stone-400, #a8a29e)" />
            </div>
        </div>
    </Card>
</template>