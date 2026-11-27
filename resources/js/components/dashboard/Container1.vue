<script setup>
    import { ref, computed, onMounted, onUnmounted } from 'vue'
    import { usePage, Link } from '@inertiajs/vue3';

    import AppIcon from '../../components/AppIcon.vue';
    import Card from './Card.vue';

    const page = usePage();
    const usuario = computed(() => page.props.auth.user);
    const rolId = computed(() => usuario.value?.role?.id);
    const name = computed(() => usuario.value.first_name ?? 'usuario');
    const last_name = computed(() => usuario.value.last_name ?? '');


    const hour = ref(new Date().getHours())
    let interval

    onMounted(() => {
        interval = setInterval(() => {
            hour.value = new Date().getHours()
        }, 60 * 1000)
    })

    onUnmounted(() => clearInterval(interval))

    const greeting = computed(() =>
        hour.value < 12 ? 'Buenos días' : hour.value < 18 ? 'Buenas tardes' : 'Buenas noches'
    )

    const text = {
        1: {
            title: 'Panel de administración',
            subtitle: 'Supervisa cuentas, estructura escolar y configuración general de Kanvu.',
            button: 'Crear usuario',
            buttonRoute: '/user_management/Dashboard_um',
        },
        2: {
            title: 'Coordinación del Departamento Médico',
            subtitle: `Buen día, ${name.value}. Este es el panorama general de tu equipo`,
            button: 'Agregar médico',
            buttonRoute: '/user_management/Dashboard_um',
        },
        3: {
            title: 'Coordinación de Psicología',
            subtitle: `Buen día, ${name.value}. Este es el panorama general de tu equipo`,
            button: 'Agregar psicólogo',
            buttonRoute: '/user_management/Dashboard_um',
        },
        4: {
            title: 'Citas',
            subtitle: 'Registro de consultas médicas de estudiantes.',
            button: 'Registrar cita',
            buttonRoute: '/appointments/Medical'
        },
        5: {
            title: '',
            button: 'Nueva cita',
            buttonRoute: '/appointments/Medical'
        }
    }


    const titles = computed(() => text[rolId.value] ?? {});
</script>

<template>
    <div>
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
            <div v-if="rolId === 5">
                <p class="text-base font-medium mb-1 text-stone-500"> {{ greeting }} </p>
                <h1 class="text-2xl sm:text-3xl font-semibold text-stone-900"> {{name}} {{last_name}} </h1>
                <p className="text-sm mt-1 text-stone400">
                    Tu agenda y seguimiento de pacientes
                </p>
            </div>
            <div v-else>
                <h1 class="text-2xl sm:text-3xl font-semibold text-stone-900">{{titles.title}}</h1>
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