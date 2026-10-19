<script setup>
    import AppIcon from '../components/AppIcon.vue';

</script>
<template>

    <header class="sticky top-0 z-50 grid items-center px-8 py-4 border-b bg-white gap-4 border-stone-100"
        style="grid-template-columns: auto 1fr auto;">

        <a href="/dashboard" class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 bg-oliveKanvu">
                <AppIcon name="leaf" :size="22" color="white" strokeWidth="1.75" />
            </div>
            <span class="text-oliveKanvu font-sans font-semibold text-xl">Kanvu</span>
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
                    <div class="hidden sm:block text-left">
                        <p class="text-sm font-semibold leading-tight text-stone900">Sofia R</p>
                        <p class="text-xs mt-0.5 text-stone400">Nómina: A01234567
                        </p>
                    </div>
                </a>
            </div>
            <div>
                <a href="" class="w-10 h-10 rounded-xl flex items-center justify-center transition-colors">
                    <AppIcon name="Menu" :size="22" color="var(--color-stone500)" :strokeWidth="1.75" />
                </a>
            </div>
        </div>
    </header>

</template>