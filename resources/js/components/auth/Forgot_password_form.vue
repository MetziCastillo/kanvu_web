<script setup>
    import { useForgotPassword } from './Forgot_password.js';

    const { email, send } = useForgotPassword();

    const emit = defineEmits(['back']);
</script>

<!-- Vista 1: Formulario de recuperación -->
<template>
    <div v-if="!send">


        <div class="flex flex-col gap-6">
            <div>
                <button @click="emit('back')"
                    class="flex items-center gap-1.5 text-sm font-medium mb-5 text-stone-500 hover:text-stone-700 transition-colors cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Volver
                </button>

                <div class="w-11 h-11 rounded-xl flex items-center justify-center mb-4 bg-olive700/10">
                    <svg class="w-5 h-5 text-olive700" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                        </path>
                    </svg>
                </div>

                <h2 class="font-display text-3xl font-semibold mb-2 text-stone-900">Restablece tu acceso</h2>
                <p class="text-sm text-stone-500">Ingresa tu correo institucional y te enviaremos un enlace de
                    recuperación.
                </p>
            </div>

            <!-- Input Component Simulation -->
            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-semibold uppercase tracking-wider text-stone-600">Correo
                    institucional</label>
                <div class="relative flex items-center">
                    <span class="absolute left-3.5 text-stone-400 pointer-events-none">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                            </path>
                        </svg>
                    </span>
                    <input type="email" placeholder="correo@universidad.edu.mx"
                        class="w-full pl-10 pr-4 py-3 text-sm rounded-xl border border-stone-200 bg-white text-stone-900 placeholder:text-stone-400 focus:outline-none focus:ring-2 focus:ring-olive700/20 focus:border-olive700 transition-all" />
                </div>
            </div>

            <!-- Btn Primary Large Full -->
            <button @click="send = true"
                class="w-full py-3.5 px-5 rounded-xl font-medium text-sm text-white bg-olive700 hover:opacity-95 shadow-sm active:scale-[0.99] transition-all cursor-pointer">
                Enviar enlace de recuperación
            </button>
        </div>
    </div>
    <div v-else>
        <div class="flex flex-col gap-5">
            <div class="w-14 h-14 rounded-2xl flex items-center justify-center bg-olive700/20">
                <svg class="w-7 h-7 text-olive700" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <div>
                <h2 class="font-display text-3xl font-semibold mb-2 text-stone-900">Revisa tu correo</h2>
                <p class="text-sm text-stone-500">
                    Enviamos un enlace a <strong class="text-stone-800"></strong>. Puede
                    tardar unos minutos.
                </p>
            </div>
            <div class="rounded-xl p-4 bg-olive700/10">
                <p class="text-xs text-olive700 font-medium">Revisa también tu carpeta de spam o correo no deseado.
                </p>
            </div>
            <button @click="emit('back')"
                class="w-full py-3 px-4 rounded-xl font-medium text-sm text-stone-600 hover:bg-stone-100 transition-colors text-center cursor-pointer">
                ← Volver al inicio de sesión
            </button>
        </div>

    </div>
</template>