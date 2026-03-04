<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import { UserPlus } from 'lucide-vue-next';
import { Stethoscope } from 'lucide-vue-next';
import PatientFormFields from '@/components/PatientFormFields.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { create, store } from '@/routes/patients';
import { type BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Cadastro de Paciente', href: create().url },
];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Cadastro de Paciente" />

        <div class="flex flex-col gap-8 py-8 px-4 sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-2xl border border-border shadow-sm">
                <div class="flex flex-col gap-4 border-b border-border bg-red-50 p-6 sm:flex-row sm:items-center">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-red-100 text-primary">
                        <Stethoscope class="size-8" />
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-foreground">
                            Ficha de Admissão
                        </h3>
                        <p class="text-sm text-muted-foreground">
                            Preencha os dados do paciente abaixo com atenção.
                        </p>
                    </div>
                </div>

                <Form v-bind="store.form()" v-slot="{ errors, processing }">
                    <PatientFormFields
                        :errors="errors"
                        :processing="processing"
                        submit-label="Cadastrar Paciente"
                    >
                        <template #submit-icon>
                            <UserPlus class="size-4" />
                        </template>
                    </PatientFormFields>
                </Form>
            </div>
        </div>
    </AppLayout>
</template>
