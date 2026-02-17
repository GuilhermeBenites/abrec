<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import { home } from '@/routes';

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <div class="bg-gray-100 flex min-h-screen flex-col items-center justify-center font-sans text-gray-900">
        <main class="w-full max-w-md px-4 sm:px-6">
            <div class="bg-white overflow-hidden rounded-2xl border border-gray-100 shadow-sm">
                <div class="p-8 pb-6 text-center">
                    <Link :href="home()"
                        class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-red-50 transition-colors hover:bg-red-100">
                        <AppLogoIcon class="h-8 w-8 text-red-600" />
                    </Link>
                    <h1 class="mb-2 text-2xl font-bold text-gray-900">
                        {{ title }}
                    </h1>
                    <p class="text-sm leading-relaxed text-gray-600">
                        {{ description }}
                    </p>
                </div>
                <div class="space-y-6 px-8 pt-2 pb-8">
                    <slot />
                </div>
            </div>
            <div class="mt-8 text-center">
                <p class="text-xs text-gray-400">
                    © 2026 Abrec. Todos os direitos reservados.
                </p>
            </div>
        </main>
    </div>
</template>
