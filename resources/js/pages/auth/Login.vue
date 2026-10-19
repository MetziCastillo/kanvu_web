<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';

import AuthLayout from '../../layouts/AuthLayout.vue';
import LoginForm from '../../components/auth/LoginForm.vue';
import ForgotPassword from '../../components/auth/Forgot_password_form.vue';

const currentView = ref('login');
</script>

<template>
    <AuthLayout>
        <LoginForm
            v-if="currentView === 'login'"
            @forgot-password="currentView = 'forgot-password'"
            @submit="router.visit('/dashboard')"
        />

        <ForgotPassword
            v-else-if="currentView === 'forgot-password'"
            @back="currentView = 'login'"
        />
    </AuthLayout>
</template>
