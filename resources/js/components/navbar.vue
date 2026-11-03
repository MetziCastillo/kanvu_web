<script setup>
    import { computed, ref } from 'vue';
    import { usePage, Link } from '@inertiajs/vue3';
    import AppIcon from '../components/AppIcon.vue';
    import AvatarInitials from '../components/Avatar_initials.vue';

    const page = usePage();
    const usuario = computed(() => page.props.auth.user);
    const name = computed(() => usuario.value?.first_name);
    const rolId = computed(() => usuario.value?.role?.id);
    const rolName = computed(() => usuario.value?.role?.name);
    const nomina = computed(() => usuario.value?.nomina);

    const openMenu = ref(false);


    const isActive = (href) => {
        if (href === '/dashboard') {
            return page.url === '/dashboard' || page.url.startsWith('/dashboard/');
        }
        return page.url === href || page.url.startsWith(href + '/');
    };

    const titleRoles = {
        1: 'Administrador',
        2: 'Jefe de Departamento Médico',
        3: 'Jefe de Departamento de Psicología',
        4: 'Enfermero',
        5: 'Psicólogo',
        6: 'Estudiante',
    };


    const menuItems = computed(() => {
        const allItems = [
            { name: 'Inicio', icon: 'home', href: '/dashboard', roles: [1, 2, 3, 4, 5] },
            { name: 'Mis citas', icon: 'calendar', href: '#', roles: [5] },
            { name: 'Citas', icon: 'calendar', href: '#', roles: [4] },
            { name: 'Mi Historial', icon: 'history', href: '#', roles: [5] },
            { name: 'Perfil', icon: 'user', href: '#', roles: [5, 4] },
            { name: 'Mi cuenta', icon: 'user', href: '#', roles: [1] },
            { name: 'Configuración', icon: 'settings', href: '#', roles: [5, 4,] },
            { name: 'Gestión del personal', icon: 'users', href: '#', roles: [3, 2] },
            { name: 'Sesiones grupales', icon: 'calendar', href: '#', roles: [3] },
            { name: 'Registros médicos', icon: 'fileText', href: '#', roles: [2] },
            { name: 'Gestión de usuarios', icon: 'users', href: '/user_management/Dashboard_um', roles: [1] },
            { name: 'Catálogos escolares', icon: 'boocksOpen', href: '#', roles: [1] },
        ];

        const currentRole = Number(rolId.value);

        // Filtramos los items que incluyan el rol actual
        return allItems.filter(item => item.roles.includes(currentRole));
    });


    const fullName = computed(() =>
        `${usuario.value?.first_name ?? ''} ${usuario.value?.last_name ?? ''}`.trim()
    );

</script>
<template>
    <header class="sticky top-0 z-50 grid items-center px-8 py-4 border-b bg-white gap-4 border-stone-100"
        style="grid-template-columns: auto 1fr auto;">

        <a href="/dashboard" class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 bg-olive700">
                <AppIcon name="leaf" :size="22" color="white" strokeWidth="1.75" />
            </div>
            <span class="text-olive700 font-sans font-semibold text-xl">Kanvu</span>
        </a>

        <div class="flex items-center gap-2 justify-self-end">
            <div>
                <a href=""
                    class="w-10 h-10 rounded-xl flex items-center justify-center hover:bg-stone-50 transition-colors">
                    <AppIcon name="Bell" :size="20" color="var(--color-stone500)" :strokeWidth="1.75" />
                </a>

            </div>
            <div>
                <a href="" class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-stone-50 transition-colors">
                    <div>
                        <AvatarInitials :name="fullName" />
                    </div>

                    <div class="hidden sm:block text-left">
                        <p class="text-sm font-semibold leading-tight text-stone900"> {{fullName}} </p>

                        <p v-if="nomina != null && [4, 5].includes(Number(rolId))" class="text-xs mt-0.5 text-stone400">
                            Nómina: {{nomina}} </p>
                        <p v-else class="text-xs mt-0.5 text-stone400"> {{ titleRoles[rolId] ?? rolName }} </p>
                    </div>
                </a>
            </div>
            <div>
                <button @click="openMenu = !openMenu"
                    class="w-10 h-10 rounded-xl flex items-center justify-center transition-colors">
                    <AppIcon name="Menu" :size="22" color="var(--color-stone500)" :strokeWidth="1.75" />
                </button>
            </div>
        </div>
    </header>


    <!-- para moviles -->
    <!-- para moviles -->
    <Transition enter-active-class="transition duration-300 ease-out" enter-from-class="opacity-0"
        enter-to-class="opacity-100" leave-active-class="transition duration-200 ease-in" leave-from-class="opacity-100"
        leave-to-class="opacity-0">

        <div v-if="openMenu" class="fixed inset-0 z-40 flex justify-end top-[61px]">
            <!-- Fondo oscuro -->
            <div class="absolute inset-0 bg-black/35" @click.self="openMenu = false"></div>

            <!-- Panel blanco -->
            <Transition enter-active-class="transition duration-300 ease-out" enter-from-class="translate-x-full"
                enter-to-class="translate-x-0" leave-active-class="transition duration-200 ease-in"
                leave-from-class="translate-x-0" leave-to-class="translate-x-full">

                <div v-if="openMenu" class="relative z-50 flex flex-col h-full shadow-2xl bg-white w-[280px]">
                    <div class="px-6 py-9 border-b border-stone100">
                        <p class="text-xs font-bold uppercase tracking-wider text-stone400">Menú</p>
                    </div>

                    <nav class="flex-1 flex flex-col gap-1 p-3 overflow-y-auto">
                        <Link v-for="item in menuItems" :key="item.name" :href="item.href" @click="openMenu = false"
                            class="flex items-center gap-4 w-full px-4 py-3.5 rounded-xl text-left transition-colors"
                            :class="isActive(item.href) ? 'bg-olive50' : 'hover:bg-stone-50'">

                        <AppIcon :name="item.icon" :size="20" :stroke-width="isActive(item.href) ? 2 : 1.75"
                            :color="isActive(item.href) ? 'var(--color-olive600)' : 'var(--color-stone400)'" />

                        <span class="text-base font-medium"
                            :class="isActive(item.href) ? 'text-olive700' : 'text-stone700'">
                            {{ item.name }}
                        </span>

                        <div v-if="isActive(item.href)" class="ml-auto w-2 h-2 rounded-full flex-shrink-0 bg-olive500">
                        </div>
                        </Link>
                    </nav>
                </div>
            </Transition>
        </div>
    </Transition>
</template>