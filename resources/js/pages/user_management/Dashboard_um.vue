<script setup>
    import { computed, ref, watch } from 'vue';
    import { usePage } from '@inertiajs/vue3';

    import Navbar from '../../components/navbar.vue';
    import headerMenu from '../../components/options/Header_menu.vue';
    import UserCreate from '../../components/options/Users_create.vue';

    const page = usePage();
    const roles = page.props.roles;
    const careers = page.props.careers;
    const departments = page.props.department

    const create_users = ref(false);

</script>


<template>

    <div class="min-h-screen flex flex-col">
        <Navbar />

        <main class="flex-1 p-8">
            <headerMenu @create-user="create_users = true" />
            <div v-if="create_users" class="fixed inset-0 z-[100] flex items-center justify-center p-4"
                style="background-color: rgba(28,28,26,0.5);">
                <UserCreate @create-user="create_users = false" :roles="roles" :careers="careers" :departments="departments"/>
            </div>
        </main>
    </div>
</template>