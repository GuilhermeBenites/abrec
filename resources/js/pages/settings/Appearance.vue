<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import Heading from '@/components/Heading.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { type BreadcrumbItem } from '@/types';
import { edit } from '@/routes/appearance';

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Configurações de aparência',
        href: edit().url,
    },
];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">

        <Head title="Configurações de aparência" />

        <h1 class="sr-only">Configurações de Aparência</h1>

        <SettingsLayout>
            <div class="space-y-6">
                <Heading variant="small" title="Configurações de aparência"
                    description="Atualize as configurações de aparência da sua conta" />
            </div>
        </SettingsLayout>
    </AppLayout>
</template>
