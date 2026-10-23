<script setup>
    import AppIcon from '../../components/AppIcon.vue';

</script>

<template>
    <div class="p-5 bg-white rounded-2xl hover:shadow-sm transition-shadow border border-Stone100">
        <p class="font-semibold text-sm mb-4 text-stone900">Próxima sesión grupal</p>
        <div class="flex items-start gap-3.5">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 bg-olive100">
                <AppIcon name="users" :size="17" color="var(--color-oliveKanvu)" />
            </div>
            <!-- esto debe ser sobre las próximas citas -->
            <div>
                <p class="font-semibold text-sm leading-snug text-stone900">Tutoría Grupal -- IS-601</p>
                <p class="text-xs mt-1 text-stone500">3 Oct 2026 * 11:00 am</p>
                <p class="text-xs mt-0.5 text-stone400">Presencial * Aula 3-B</p>
                <p class="text-xs mt-1 font-medium text-oliveKanvu">18 Estudiantes</p>
            </div>
        </div>
    </div>
</template>