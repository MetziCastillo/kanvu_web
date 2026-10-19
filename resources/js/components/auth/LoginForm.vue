<script setup>
    import { ref } from "vue";

    const email = ref("");
    const password = ref("");
    const remember = ref(false);

    const emit = defineEmits(["forgot-password", "register", "submit"]);

    function handleSubmit() {
        emit("submit", {
            email: email.value,
            password: password.value,
            remember: remember.value,
        });
    }
</script>

<template>
    <section>
        <form @submit.prevent="handleSubmit" class="flex flex-col gap-7">
            <div>
                <h2 class="font-display text-3xl font-semibold mb-2 text-stone-900">
                    Bienvenido de vuelta
                </h2>

                <p class="text-sm text-stone-500">
                    Inicia sesión con tu cuenta institucional.
                </p>
            </div>

            <div class="flex flex-col gap-4">
                <div class="flex flex-col gap-1.5">
                    <label for="email" class="text-sm font-medium text-stone-700">
                        Correo institucional
                    </label>

                    <input id="email" v-model="email" type="email" autocomplete="username"
                        placeholder="correo@universidad.edu.mx" required
                        class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-stone-900 placeholder:text-stone-400 focus:outline-none focus:ring-2 focus:ring-emerald-600 text-sm" />
                </div>

                <div class="flex flex-col gap-1.5">
                    <label for="password" class="text-sm font-medium text-stone-700">
                        Contraseña
                    </label>

                    <input id="password" v-model="password" type="password" autocomplete="current-password"
                        placeholder="••••••••" required
                        class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-stone-900 placeholder:text-stone-400 focus:outline-none focus:ring-2 focus:ring-emerald-600 text-sm" />
                </div>
            </div>

            <div class="flex items-center justify-between gap-3">
                <label class="flex items-center gap-2 text-sm cursor-pointer text-stone-500">
                    <input v-model="remember" type="checkbox"
                        class="rounded border-stone-300 text-emerald-600 focus:ring-emerald-500" />
                    Recordarme
                </label>

                <button type="button" @click="emit('forgot-password')"
                    class="text-sm font-semibold text-emerald-700 hover:underline">
                    ¿Olvidaste tu contraseña?
                </button>
            </div>

            <button type="submit" @submit="router.visit('/dashboard')"
                class="w-full py-3 px-4 bg-oliveKanvu hover:bg-emerald-800 text-white font-semibold rounded-xl text-base transition-colors shadow-sm">
                Iniciar sesión
            </button>

            <!-- <p class="text-center text-sm text-stone-500">
                ¿Aún no tienes cuenta?

                <button type="button" @click="emit('register')" class="font-semibold text-emerald-700 hover:underline">
                    Crear cuenta
                </button>
            </p> -->
        </form>
    </section>
</template>