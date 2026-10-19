<template>
    <div class="min-h-screen flex bg-stone-100">
        <!-- Panel izquierdo: identidad de Kanvu -->
        <aside
            class="hidden lg:flex flex-col justify-between w-[480px] shrink-0 p-12 relative overflow-hidden bg-oliveKanvu">
            <!-- Decoración -->
            <div class="absolute -top-24 -left-24 w-96 h-96 rounded-full opacity-10 border-[80px] border-emerald-300">
            </div>

            <div
                class="absolute -bottom-32 -right-20 w-80 h-80 rounded-full opacity-10 border-[60px] border-emerald-300">
            </div>

            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-16">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"
                            class="text-white">
                            <path
                                d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z" />
                            <path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.5 12 13 13 11" />
                        </svg>
                    </div>

                    <span class="font-display text-2xl font-semibold text-white">
                        Kanvu
                    </span>
                </div>

                <h1 class="font-display text-4xl font-semibold leading-tight text-white mb-4">
                    Tu bienestar es parte de tu formación.
                </h1>

                <p class="text-base leading-relaxed text-greenLight">
                    Accede a orientación psicoeducativa y psicología, agenda
                    citas y participa en actividades de bienestar, todo en un
                    solo lugar.
                </p>
            </div>

            <!-- Testimonio ilustrativo -->
            <div class="relative z-10 rounded-2xl p-5 bg-white/[0.08]">
                <p class="text-sm leading-relaxed italic mb-3 text-emerald-100">
                    "Kanvu me ayudó a tomar el primer paso. Fue mucho más fácil
                    de lo que esperaba."
                </p>

                <div class="flex items-center gap-3">
                    <div
                        class="w-8 h-8 rounded-lg flex items-center justify-center text-xs font-semibold bg-emerald-600 text-white">
                        MR
                    </div>

                    <div>
                        <p class="text-xs font-semibold text-white">María R.</p>
                        <p class="text-xs text-emerald-300">
                            Estudiante de 4.° semestre
                        </p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Panel derecho: contenido de cada pantalla -->
        <main class="flex-1 flex items-center justify-center p-6 lg:p-16">
            <div class="w-full max-w-md">
                <!-- Logo visible en móvil -->
                <div class="flex items-center gap-3 mb-10 lg:hidden">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center bg-oliveKanvu">
                        <span class="text-white font-semibold"></span>
                    </div>

                    <span class="font-display text-xl font-semibold text-stone-900">
                        Kanvu
                    </span>
                </div>

                <slot />
            </div>
        </main>
    </div>
</template>