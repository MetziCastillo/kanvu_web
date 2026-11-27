<script setup>
    import { computed } from 'vue'
    import { router } from '@inertiajs/vue3'

    import AppIcon from '../../components/AppIcon.vue';
    import Statusbadge from './StatusBadge.vue';

    const props = defineProps({
        next: { type: Object, default: null }
    })

    const emit = defineEmits(['select-appointment', 'navigate'])

    const studentInitials = computed(() => {
        if (!props.next?.studentName) return ''
        return props.next.studentName
            .split(' ')
            .map(word => word[0])
            .join('')
            .slice(0, 2)
            .toUpperCase()
    })

    const formattedDate = computed(() => {
        if (!props.next?.date) return ''
        return props.next.date
    })

    const formattedTime = computed(() => {
        if (!props.next) return ''
        return `${props.next.startTime} - ${props.next.endTime}`
    })

    const handleOpenAppointment = (id) => {
        // Opcional: si aún necesitas emitir el evento para el padre
        emit('select-appointment', id)

        // Navegamos a la ruta de Laravel pasando el ID
        router.visit(`/appointments/psychology/${id}`)
    }
</script>

<template>
    <div class="lg:col-span-2 flex flex-col gap-5">

        <div v-if="next" class="rounded-3xl p-7 relative overflow-hidden bg-gradient-to-br from-olive800 to-olive600">
            <div
                class="absolute -right-12 -top-20 w-64 h-64 rounded-full opacity-10 border-[44px] border-solid border-white pointer-events-none" />

            <div class="relative">
                <div class="flex items-center justify-between mb-5">
                    <p class="text-xs font-bold uppercase tracking-widest text-olive200">
                        Siguiente paciente
                    </p>
                    <Statusbadge :name="next.status_id" />
                </div>

                <div class="flex items-center gap-4 mb-5">
                    <div
                        class="w-12 h-12 rounded-xl flex items-center justify-center font-semibold bg-white/[.14] text-white">
                        {{ studentInitials }}
                    </div>
                    <div>
                        <p class="font-display text-2xl font-semibold text-white">
                            {{ next.studentName }}
                        </p>
                        <p class="text-sm mt-0.5 text-olive200">
                            {{ next.type }} · {{ next.matricula }}
                        </p>
                    </div>
                </div>

                <div class="flex flex-wrap gap-4 text-sm text-olive200">
                    <span class="flex items-center gap-2">
                        <AppIcon name="calendar" :size="15" />
                        {{ formattedDate }}
                    </span>
                    <span class="flex items-center gap-2">
                        <AppIcon name="clock" :size="15" />
                        {{ formattedTime }}
                    </span>
                    <span class="flex items-center gap-2">
                        <AppIcon :name="next.modality === 'En línea' ? 'monitor' : 'mapPin'" :size="15" />
                        {{ next.modality }}
                    </span>
                </div>

                <button @click="handleOpenAppointment(next.id)"
                    class="mt-6 px-5 py-2.5 rounded-xl text-sm font-semibold bg-white text-olive700 hover:bg-stone100 transition-colors">
                    Abrir expediente
                </button>
            </div>
        </div>

        <div v-else
            class="rounded-3xl p-7 border-2 border-dashed border-stone300 bg-stone100/50 flex flex-col items-center justify-center text-center h-full min-h-[220px]">
            <div
                class="w-14 h-14 rounded-full bg-stone100 flex items-center justify-center text-stone400 mb-4 shadow-sm">
                <AppIcon name="calendar" :size="24" />
            </div>
            <p class="font-display text-xl font-semibold text-stone900 mb-1">
                Sin pacientes próximos
            </p>
            <p class="text-sm text-stone500 max-w-xs">
                No tienes ninguna cita agendada para los próximos 15 minutos.
            </p>
        </div>

    </div>
</template>