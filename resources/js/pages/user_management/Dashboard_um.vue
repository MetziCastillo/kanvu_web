<script setup>
    import { ref, computed } from 'vue';
    import { usePage, router } from '@inertiajs/vue3';
    import AppIcon from '../../components/AppIcon.vue';

    import Navbar from '../../components/navbar.vue';
    import headerMenu from '../../components/options/Header_menu.vue';
    import UserCreate from '../../components/options/Users_create.vue';
    import CardUserManagement from '../../components/dashboard/Card_user_management.vue';

    const page = usePage();
    const roles = page.props.roles;
    const careers = page.props.careers;
    const departments = page.props.department;
    const allUsers = computed(() => page.props.allUsers);

    const isRefreshing = ref(false);
    const toastMessage = ref('');
    let toastTimeout = null;

    const showForm = ref(false);
    const editingUser = ref(null);

    const openCreate = () => {
        editingUser.value = null;
        showForm.value = true;
    };

    const openEdit = (user) => {
        editingUser.value = user;
        showForm.value = true;
    };

    const closeForm = () => {
        showForm.value = false;
        editingUser.value = null;
    };

    const refreshUsers = (isEdit = false) => {
        isRefreshing.value = true;
        showForm.value = false;

        router.reload({
            only: ['allUsers'],
            onFinish: () => {
                isRefreshing.value = false;
                showToast(isEdit ? 'Usuario actualizado correctamente' : 'Usuario creado correctamente');
            }
        });
    };

    const showToast = (msg) => {
        toastMessage.value = msg;
        if (toastTimeout) clearTimeout(toastTimeout);
        toastTimeout = setTimeout(() => {
            toastMessage.value = '';
        }, 3000);
    };

    const processingUserId = ref(null);

    const handleUpdateStatus = (userId, newStatus) => {
        if (processingUserId.value) return;

        // Confirmación para "Baja" (acción destructiva)
        if (newStatus === 'Baja') {
            const user = allUsers.value.find(u => u.user_id === userId);
            const nombre = user ? `${user.name} ${user.last_name}` : 'este usuario';
            if (!confirm(`¿Dar de baja a ${nombre}? Esta acción cambiará su estado a "Baja".`)) {
                return;
            }
        }

        // Lock inmediato
        processingUserId.value = userId;

        router.patch(`/users/${userId}/status`, {
            status_name: newStatus,
        }, {
            preserveScroll: true,
            only: ['allUsers'],
            onSuccess: () => {
                const mensajes = {
                    Baja: 'Usuario dado de baja',
                    Suspendida: 'Usuario suspendido',
                    Activa: 'Usuario reactivado',
                };
                showToast(mensajes[newStatus] ?? 'Estado actualizado');
            },
            onError: (errors) => {
                showToast(errors.status ?? 'No se pudo actualizar el estado');
            },
            onFinish: () => { processingUserId.value = null; },
        });
    };
</script>

<template>
    <div class="min-h-screen flex flex-col">
        <Navbar />

        <main class="flex-1 p-8">
            <headerMenu @create-user="openCreate" />

            <div v-if="showForm" class="fixed inset-0 z-[100] flex items-center justify-center p-4"
                style="background-color: rgba(28,28,26,0.5);">
                <UserCreate @create-user="closeForm" @user-created="refreshUsers(false)"
                    @user-updated="refreshUsers(true)" :roles="roles" :careers="careers" :departments="departments"
                    :editing-user="editingUser" />
            </div>

            <div class="relative">
                <div v-if="isRefreshing"
                    class="absolute inset-0 z-10 bg-white/60 backdrop-blur-[1px] flex items-center justify-center rounded-2xl transition-opacity">
                    <div
                        class="flex flex-col items-center gap-3 bg-white px-6 py-4 rounded-xl shadow-lg border border-stone-100">
                        <svg class="size-6 text-olive700 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        <span class="text-sm font-semibold text-olive700">Actualizando tabla...</span>
                    </div>
                </div>

                <CardUserManagement :all-users="allUsers" :processing-user-id="processingUserId" @edit="openEdit"
                    @update-status="handleUpdateStatus" />
            </div>
        </main>

        <div v-if="toastMessage"
            class="fixed bottom-6 right-6 z-[200] flex items-center gap-3 bg-olive700 text-white px-5 py-3 rounded-xl shadow-xl animate-bounce-in">
            <AppIcon name="check" size="20" color="white" />
            <span class="text-sm font-medium">{{ toastMessage }}</span>
        </div>
    </div>
</template>

<style scoped>
    @keyframes bounce-in {
        0% {
            transform: translateY(20px);
            opacity: 0;
        }

        100% {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .animate-bounce-in {
        animation: bounce-in 0.3s ease-out;
    }
</style>