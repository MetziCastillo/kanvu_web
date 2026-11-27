<script setup>
    import { computed } from 'vue'
    import { usePage, Link } from '@inertiajs/vue3'

    import Navbar from '../../components/navbar.vue';
    import Card from '../../components/Cards.vue';
    import AvatarInitials from '../../components/Avatar_initials.vue';
    import AppIcon from '../../components/AppIcon.vue';
    import Statusbadge from '../../components/dashboard/StatusBadge.vue';

    const page = usePage();
    const usuario = computed(() => page.props.auth.user);
    const rolId = computed(() => usuario.value?.role?.id);
    const d = computed(() => page.props.detailsAppointment)



    console.log('DETALES : ', d.value)

    const formatTime = (t) => t ? t.slice(0, 5) : '—';

    const titles = computed(() => {
        const rol = rolId.value
        if (!rol) return []

        const map = {
            4: { return: 'Volver a consultas', title2: 'Resumen clínico', subtitle: 'Información registrada durante la atención.', final: 'Acceso exclusivo para personal médico autorizado.' },
            5: { return: 'Volver a mi agenda', title2: 'Información de la consulta', subtitle: 'Registro asociado a tu cuenta profesional.', final: 'Este expediente es confidencial y solo está disponible para el psicólogo asignado.' }
        }

        return map[rol] ?? []
    })

    const valuesCards = {
        4: [
            {
                title: 'Fecha',
                value: 'consultation_date',
                icon: 'calendar'
            },
            {
                title: 'Inicio',
                value: 'time_start',
                icon: 'clock'
            },
            {
                title: 'Término',
                value: 'time_end',
                icon: 'check'
            },
        ],
        5: [
            {
                title: 'Fecha',
                value: 'consultation_date',
                icon: 'calendar'
            },
            {
                title: 'Horario',
                value: 'time_start',
                icon: 'clock'
            },
            {
                title: 'Modalidad',
                value: 'modality_name',
                icon: 'check'
            },
        ]
    }

    const recicle = computed(() => valuesCards[rolId.value] ?? []);

    const getModalityIcon = (modality) => modality === 'En línea' ? 'monitor' : 'mapPin';

    const academicDates = [
        {
            title: 'Matrícula',
            value: 'matricula',
            icon: 'shield'
        },
        {
            title: 'Carrera',
            value: 'career_name',
            icon: 'graduationCap'
        },
        {
            title: 'Grado',
            value: 'grade_name',
            icon: 'boocksOpen'
        },
        {
            title: 'Grupo',
            value: 'group_name',
            icon: 'users'
        }
    ]

    const chronology = [
        { title: 'Inicio de consulta', value: 'time_start', isKey: true },
        { title: 'Valoración y registro', value: 'Durante la consulta', isKey: false },
        { title: 'Término de consulta', value: 'time_end', isKey: true },
    ]


</script>

<template>
    <div class="min-h-screen flex flex-col">
        <Navbar />

        <main class="flex-1 p-8">
            <div class="max-w-6xl mx-auto">
                <div class="flex items-center justify-between mb-6">
                    <Link href="/dashboard" class="flex items-center gap-2 text-sm font-semibold text-stone500">
                    <span class="w-9 h-9 rounded-xl flex items-center justify-center bg-white border border-stone100">
                        <AppIcon name="chevronLeft" size="18" color="var(--color-stone700)" />
                    </span>
                    {{ titles.return}}
                    </Link :href>
                    <span v-if="rolId === 4"
                        class="text-xs font-semibold px-3 py-1.5 rounded-full bg-olive100 text-olive700">
                        Consulta completada
                    </span>
                    <!-- <span class="text-xs font-semibold px-3 py-1.5 rounded-full bg-olive100 text-olive700">
                        Consulta completada
                    </span> -->
                    <Statusbadge :name="d.status_id"/>

                </div>

                <div v-if="rolId === 5"
                    class="rounded-3xl overflow-hidden mb-6 relative bg-[linear-gradient(135deg,var(--color-olive800),var(--color-olive600))]">
                    <div
                        class="pointer-events-none absolute -right-12 -top-24 w-72 h-72 rounded-full opacity-10 border-[48px] border-solid border-white">
                    </div>

                    <div class="relative p-7 md:p-9 flex flex-col md:flex-row md:items-end justify-between gap-8">
                        <div class="flex items-start gap-5">
                            <AvatarInitials :name="`${d.first_name} ${d.last_name}`" size="xl" />
                            <div>
                                <p class="text-xs font-bold uppercase tracking-widest mb-2 text-olive-200">
                                    Sesión grupal
                                </p>
                                <h1 class="font-display text-3xl md:text-4xl font-semibold text-white">
                                    {{d.first_name}} {{d.last_name}}
                                </h1>
                                <p class="text-sm mt-2 text-olive200">
                                    {{d.matricula}} · {{d.career_name}} · {{d.group_name}}
                                </p>
                            </div>
                        </div>
                        <div class="grid grid-cols-3 gap-3 md:min-w-[430px]">
                            <div v-for="item in recicle" :key="item.title" class="rounded-2xl p-3.5 bg-[#ffffff1c]">
                                <AppIcon :name="item.icon" size="16" color="var(--color-olive200)" />
                                <p class="text-[10px] uppercase tracking-wider mt-2 text-olive300">
                                    {{item.title}}
                                </p>
                                <p class="text-sm font-semibold text-white mt-0.5 truncate">
                                    {{ d[item.value] }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <Card v-if="rolId === 4" padding="p-0" className="overflow-hidden mb-6">
                    <div class="h-2 bg-olive600"></div>
                    <div class="p-7 md:p-8 flex flex-col lg:flex-row lg:items-center gap-7">
                        <div class="flex items-center gap-5 flex-1">
                            <AvatarInitials :name="`${d.first_name} ${d.last_name}`" size="xl" />
                            <div>
                                <p class="text-xs font-bold uppercase tracking-wider mb-1.5 text-olive600">Expediente de
                                    consuta</p>
                                <h1 class="text-3xl font-semibold text-stone900"> {{d.first_name}}
                                    {{d.last_name}} </h1>
                                <p class="text-sm mt-1.5 text-stone500"> {{d.matricula}} · {{d.career_name}} </p>
                            </div>
                        </div>
                        <div class="grid grid-cols-3 gap-3 lg:w-[470px]">
                            <div v-for="item in recicle" :key="item.title" class="rounded-2xl p-4 bg-stone100">
                                <AppIcon :name="item.icon" size="17" color="var(--color-olive600)" />
                                <p class="text-[10px] uppercase tracking-wider mt-2 text-stone400" text-stone400>
                                    {{item.title}} </p>
                                <p class="text-sm font-semibold mt-0.5 text-stone900">
                                    {{ d[item.value] }}
                                </p>
                            </div>
                        </div>
                    </div>
                </Card>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2 flex flex-col gap-6">
                        <Card padding="p-0" className="overflow-hidden">
                            <div class="px-6 py-5 border-b flex items-center gap-3 border-stone100">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-sand">
                                    <AppIcon name="fileText" size="19" color="#7a6a30" />
                                </div>
                                <div>
                                    <p class="font-semibold text-stone90">
                                        {{titles.title2}}
                                    </p>
                                    <p class="text-xs mt-0.5 text-stone500">
                                        {{titles.subtitle}}
                                    </p>
                                </div>
                            </div>
                            <div class="p-6">
                                <p class="text-xs font-bold uppercase tracking-wider text-stone400">
                                    Motivo de consulta
                                </p>
                                <p class="text-base leading-relaxed mt-3 text-stone700">
                                    {{d.reason}}
                                </p>
                            </div>

                            <div class="mx-6 mb-6 rounded-2xl p-5 bg-stone100">
                                <div class="flex items-center gap-2 mb-2">
                                    <AppIcon name="messageCircle" size="16" color="var(--color-stone500)" />
                                    <p class="text-xs font-bold uppercase tracking-wider text-stone500">
                                        Observaciones generales
                                    </p>
                                </div>
                                <p class="text-sm leading-relaxed text-stone700">
                                    {{ d.notes || 'Sin Observaciones' }}
                                </p>
                            </div>
                        </Card>

                        <div class="rounded-2xl p-6 bg-olive700">
                            <div class="flex items-start gap-4">
                                <div
                                    class="w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0 bg-[#ffffff1f]">
                                    <AppIcon name="check" size="20" color="white" />
                                </div>
                                <div>
                                    <p class="text-xs font-bold uppercase tracking-wider text-olive200">
                                        Indicaciones y seguimiento
                                    </p>
                                    <p class="text-base leading-relaxed mt-2 text-white">
                                        {{ d.instructions || 'Sin indicaciones' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col gap-5">
                        <Card padding="p-5" v-if="rolId === 5">
                            <div class="flex items-start gap-3 mb-4">
                                <div
                                    class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 bg-olive50">
                                    <AppIcon name="check" size="19" color="var(--color-olive600)" />
                                </div>
                                <div>
                                    <p class="font-semibold text-stone900">
                                        Registrar asistencia
                                    </p>
                                    <p class="text-xs leading-relaxed mt-1 text-stone500">
                                        La hora de la cita ya llegó. Selecciona el resultado de la
                                        asistencia.
                                    </p>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-2.5">
                                <button type="button"
                                    class="rounded-xl border px-3 py-3 text-sm font-semibold flex items-center justify-center gap-2 transition-colors border-olive-600 bg-olive-100 text-olive-700">
                                    <AppIcon name="check" size="16" color="#4D5630" />
                                    Asistió
                                </button>

                                <button type="button"
                                    class="rounded-xl border px-3 py-3 text-sm font-semibold flex items-center justify-center gap-2 transition-colors border-stone-300 bg-white text-olive-700">
                                    <AppIcon name="x" size="16" color="#9B3525" />
                                    No asistió
                                </button>
                            </div>
                            <p class="text-xs text-center mt-3 text-stone500">
                                Asistencia registrada. Puedes cambiar la selección si fue
                                necesario.
                            </p>
                        </Card>


                        <Card padding="p-0">
                            <div class="px-5 py-4 border-b border-stone100">
                                <p class="font-semibold text-stone900">
                                    Datos académicos
                                </p>
                            </div>

                            <div class="p-5 flex flex-col gap-4">
                                <div v-for="ad in academicDates" :key="ad.title" class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl flex items-center justify-center bg-olive50">
                                        <AppIcon :name="ad.icon" size="16" color="var(--color-olive600)" />
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-xs text-stone400">
                                            {{ad.title}}
                                        </p>
                                        <p class="text-sm font-semibold mt-0.5 truncate text-stone700">
                                            {{ d[ad .value] }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </Card>


                        <Card padding="p-5">
                            <p class="text-xs font-bold uppercase tracking-wider mb-4 text-stone400">
                                Cronología de atención
                            </p>
                            <div
                                class="relative pl-5 flex flex-col gap-5 before:absolute before:left-[5px] before:top-2 before:bottom-2 before:w-px before:bg-stone-200">
                                <div v-for="(item, index) in chronology" :key="item.title" class="relative">
                                    <span class="absolute -left-5 top-1 w-2.5 h-2.5 rounded-full"
                                        :class="index === 2 ? 'bg-olive600' : 'bg-olive300'"></span>
                                    <p class="text-sm font-semibold text-stone700">
                                        {{item.title}}
                                    </p>
                                    <p class="text-xs mt-0.5 text-stone400">
                                        {{ item.isKey ? (d[item.value] ?? '—') : item.value }}
                                    </p>
                                </div>
                            </div>
                        </Card>


                        <div class="rounded-2xl p-5 flex gap-3 items-start bg-olive50">
                            <AppIcon name="shield" size="18" color="var(--color-olive600)" />
                            <div>
                                <p class="text-sm font-semibold text-olive700">
                                    Expediente confidencial
                                </p>
                                <p class="text-xs leading-relaxed mt-1 text-olive600">
                                    {{titles.final}}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>