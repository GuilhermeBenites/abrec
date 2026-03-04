<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import { Pencil, Stethoscope } from 'lucide-vue-next';
import PatientFormFields from '@/components/PatientFormFields.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { edit, index, update } from '@/routes/patients';
import { type BreadcrumbItem } from '@/types';

const props = defineProps<{
    patient: {
        id: number;
        name: string;
        cpf: string;
        date_of_birth: string;
        gender: string;
        address: string;
        neighborhood: string;
        city: string;
        phone: string | null;
        weight: string;
        height: string;
        blood_pressure: string;
        blood_glucose: string;
        creatinine: string;
        is_diabetic: boolean;
        is_hypertensive: boolean;
        has_kidney_problem: boolean;
        has_family_drc: boolean;
        is_obese: boolean;
        has_back_eye_exam: boolean;
    };
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Lista de Pacientes', href: index().url },
    { title: 'Editar Paciente', href: edit({ patient: props.patient.id }).url },
];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Editar Paciente" />

        <div class="flex flex-col gap-8 py-8 px-4 sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-2xl border border-border shadow-sm">
                <div class="flex flex-col gap-4 border-b border-border bg-red-50 p-6 sm:flex-row sm:items-center">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-red-100 text-primary">
                        <Stethoscope class="size-8" />
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-foreground">
                            Editar Paciente
                        </h3>
                        <p class="text-sm text-muted-foreground">
                            Atualize os dados do paciente abaixo.
                        </p>
                    </div>
                </div>

                <Form
                    :action="update.url({ patient: patient.id })"
                    method="put"
                    v-slot="{ errors, processing }"
                >
                    <PatientFormFields
                        :errors="errors"
                        :processing="processing"
                        :patient="patient"
                        submit-label="Salvar Alterações"
                    >
                        <template #submit-icon>
                            <Pencil class="size-4" />
                        </template>
                    </PatientFormFields>
                </Form>
            </div>
        </div>
    </AppLayout>
</template>
