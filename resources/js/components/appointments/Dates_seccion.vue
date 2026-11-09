<script setup>
    import { ref, computed } from 'vue';
    import { usePage, router, Link } from '@inertiajs/vue3';

    import AppIcon from '../../components/AppIcon.vue';
    import AvatarInitials from '../../components/Avatar_initials.vue';

    const props = defineProps({
        appointments: { type: Array, required: true },
    });
</script>

<template>
    <div>
        <p class="ext-xs font-bold uppercase tracking-wider mb-3 text-stone400">Consultas registradas</p>

        <div v-if="appointments.length === 0"
            class="p-16 bg-white rounded-2xl hover:shadow-sm transition-shadow border border-stone-100">
            <div class="flex flex-col items-center gap-3 text-center">
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center bg-olive50">
                    <AppIcon name="fileText" size="22" color="var(--color-olive400)" />
                </div>
                <p class="font-semibold text-sm text-stone700">Sin consultas registradas</p>
                <p class="text-sm text-stone500">Registra la primera consulta médica.</p>

                <Link href="/appointments/Medical"
                    class="bg-olive600 text-white rounded-xl font-semibold transition-all active:scale-[0.97] select-none cursor-pointer inline-flex items-center justify-center gap-2 px-4 py-2 text-sm">
                Registrar
                cita</Link>
            </div>
        </div>

        <div v-else class="p-0 bg-white rounded-2xl border border-stone100">
            <Link v-for="ct in appointments" :key="ct.id" :href="`/appointments/${ct.id}`"
                title="Ver detalles de consulta"
                class="flex items-center gap-5 px-6 py-5 border-b last:border-b-0 hover:bg-stone-50 cursor-pointer transition-colors border-stone100 text-stone100">


            <div class="w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0 bg-stone100">
                <AvatarInitials :name="`${ct.first_name} ${ct.last_name}`" size="md" />
            </div>
            <div class="flex-1 min-w-0">
                <p class="font-semibold text-base truncate text-stone900">
                    {{ct.first_name}} {{ct.last_name}}
                </p>
                <p class="text-sm mt-0.5 truncate text-stone500">
                    {{ct.career_name}} · {{ct.grade_name}}{{ct.group_name}}
                </p>
            </div>
            <div class="hidden md:flex items-center gap-5 text-sm text-stone500">
                <span class="flex items-center gap-1.5">
                    <AppIcon name="calendar" size="15" color="var(--color-olive400)" />
                    {{ct.consultation_date}}
                </span>
                <span class="flex items-center gap-1.5">
                    <AppIcon name="clock" size="15" color="var(--color-olive400)" />
                    {{ct.time_start}}-{{ct.time_end}}
                </span>
                <span class="flex items-center gap-1.5">
                    <AppIcon name="fileText" size="15" color="var(--color-olive400)" />
                    {{ct.reason}}
                </span>
            </div>
            <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-stone100 text-stone700">Completada</span>
            <AppIcon name="chevronRight" size="16" color="var(--color-stone300)" />

            </Link>
        </div>

    </div>

</template>