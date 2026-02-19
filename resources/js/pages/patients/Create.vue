<script setup lang="ts">
import { Form, Head, Link } from '@inertiajs/vue3';
import {
    Activity,
    Droplet,
    Eye,
    Heart,
    HeartPulse,
    Info,
    Scale,
    Stethoscope,
    User,
    UserPlus,
    Users,
} from 'lucide-vue-next';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Toggle } from '@/components/ui/toggle';
import { Spinner } from '@/components/ui/spinner';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { create, index, store } from '@/routes/patients';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Cadastro de Paciente', href: create().url },
];

</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Cadastro de Paciente" />

        <div class="flex flex-col gap-8 py-8 px-4 sm:px-6 lg:px-8">
            <div
                class="overflow-hidden rounded-2xl border border-border shadow-sm"
            >
                <div
                    class="flex flex-col gap-4 border-b border-border bg-red-50 p-6 sm:flex-row sm:items-center"
                >
                    <div
                        class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-red-100 text-primary"
                    >
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

                <Form
                    v-bind="store.form()"
                    class="grid grid-cols-1 gap-8 p-6 lg:grid-cols-12 lg:gap-12 lg:p-8 bg-white"
                    v-slot="{ errors, processing }"
                >
                    <div class="space-y-6 lg:col-span-7">
                        <div class="mb-4 flex items-center gap-2">
                            <User class="size-4 text-primary" />
                            <span
                                class="text-xs font-bold uppercase tracking-wider text-primary"
                            >
                                Dados Pessoais
                            </span>
                        </div>

                        <div class="space-y-1">
                            <Label for="name">Nome Completo</Label>
                            <Input
                                id="name"
                                name="name"
                                type="text"
                                required
                                placeholder="Ex: João da Silva"
                            />
                            <InputError :message="errors.name" />
                        </div>

                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div class="space-y-1">
                                <Label for="date_of_birth">Nascimento</Label>
                                <Input
                                    id="date_of_birth"
                                    name="date_of_birth"
                                    type="date"
                                    required
                                />
                                <InputError :message="errors.date_of_birth" />
                            </div>
                            <div class="space-y-2">
                                <Label>Sexo</Label>
                                <div
                                    class="flex h-[42px] rounded-lg border border-input bg-muted/50 p-1"
                                >
                                    <input
                                        id="gender-male"
                                        name="gender"
                                        type="radio"
                                        value="male"
                                        class="peer/m sr-only"
                                    />
                                    <label
                                        for="gender-male"
                                        class="flex flex-1 cursor-pointer items-center justify-center rounded-md text-center text-sm font-medium text-muted-foreground transition-all peer-checked/m:bg-background peer-checked/m:font-medium peer-checked/m:text-primary peer-checked/m:shadow-sm"
                                    >
                                        Masculino
                                    </label>
                                    <input
                                        id="gender-female"
                                        name="gender"
                                        type="radio"
                                        value="female"
                                        class="peer/f sr-only"
                                    />
                                    <label
                                        for="gender-female"
                                        class="flex flex-1 cursor-pointer items-center justify-center rounded-md text-center text-sm font-medium text-muted-foreground transition-all peer-checked/f:bg-background peer-checked/f:font-medium peer-checked/f:text-primary peer-checked/f:shadow-sm"
                                    >
                                        Feminino
                                    </label>
                                </div>
                                <InputError :message="errors.gender" />
                            </div>
                        </div>

                        <div class="space-y-1">
                            <Label for="cpf">CPF</Label>
                            <Input
                                id="cpf"
                                name="cpf"
                                type="text"
                                required
                                placeholder="000.000.000-00"
                                maxlength="14"
                            />
                            <InputError :message="errors.cpf" />
                        </div>

                        <div class="space-y-1">
                            <Label for="address">Endereço</Label>
                            <Input
                                id="address"
                                name="address"
                                type="text"
                                required
                                placeholder="Rua, Número, Complemento"
                            />
                            <InputError :message="errors.address" />
                        </div>

                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div class="space-y-1">
                                <Label for="neighborhood">Bairro</Label>
                                <Input
                                    id="neighborhood"
                                    name="neighborhood"
                                    type="text"
                                    required
                                    placeholder="Bairro"
                                />
                                <InputError :message="errors.neighborhood" />
                            </div>
                            <div class="space-y-1">
                                <Label for="city">Cidade</Label>
                                <Input
                                    id="city"
                                    name="city"
                                    type="text"
                                    required
                                    placeholder="Cidade"
                                />
                                <InputError :message="errors.city" />
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6 lg:col-span-5">
                        <div class="mb-4 flex items-center gap-2">
                            <Activity class="size-4 text-primary" />
                            <span
                                class="text-xs font-bold uppercase tracking-wider text-primary"
                            >
                                Informações de Saúde
                            </span>
                        </div>

                        <div class="mb-4 grid grid-cols-2 gap-4">
                            <div class="space-y-1">
                                <Label for="weight">Peso (kg)</Label>
                                <Input
                                    id="weight"
                                    name="weight"
                                    type="number"
                                    step="0.1"
                                    placeholder="00.0"
                                />
                                <InputError :message="errors.weight" />
                            </div>
                            <div class="space-y-1">
                                <Label for="height">Altura (cm)</Label>
                                <Input
                                    id="height"
                                    name="height"
                                    type="number"
                                    placeholder="000"
                                />
                                <InputError :message="errors.height" />
                            </div>
                        </div>

                        <div
                            class="divide-y divide-border overflow-hidden rounded-xl border border-border bg-muted/30"
                        >
                            <div
                                class="flex items-center justify-between p-4 transition-colors hover:bg-muted/50"
                            >
                                <div class="flex items-center gap-3">
                                    <div
                                        class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-100"
                                    >
                                        <Droplet
                                            class="size-5 text-blue-500"
                                        />
                                    </div>
                                    <span class="font-medium">Diabético</span>
                                </div>
                                <Toggle name="is_diabetic" />
                            </div>
                            <div
                                class="flex items-center justify-between p-4 transition-colors hover:bg-muted/50"
                            >
                                <div class="flex items-center gap-3">
                                    <div
                                        class="flex h-10 w-10 items-center justify-center rounded-full bg-red-100"
                                    >
                                        <Heart
                                            class="size-5 text-red-500"
                                        />
                                    </div>
                                    <span class="font-medium">Hipertenso</span>
                                </div>
                                <Toggle name="is_hypertensive" />
                            </div>
                            <div
                                class="flex items-center justify-between p-4 transition-colors hover:bg-muted/50"
                            >
                                <div class="flex items-center gap-3">
                                    <div
                                        class="flex h-10 w-10 items-center justify-center rounded-full bg-orange-100"
                                    >
                                        <HeartPulse
                                            class="size-5 text-orange-500"
                                        />
                                    </div>
                                    <span class="font-medium"
                                        >Problema Renal</span
                                    >
                                </div>
                                <Toggle name="has_kidney_problem" />
                            </div>
                            <div
                                class="flex items-center justify-between p-4 transition-colors hover:bg-muted/50"
                            >
                                <div class="flex items-center gap-3">
                                    <div
                                        class="flex h-10 w-10 items-center justify-center rounded-full bg-yellow-100"
                                    >
                                        <Users
                                            class="size-5 text-yellow-600"
                                        />
                                    </div>
                                    <span class="font-medium"
                                        >D.R.C na Família</span
                                    >
                                </div>
                                <Toggle name="has_family_drc" />
                            </div>
                            <div
                                class="flex items-center justify-between p-4 transition-colors hover:bg-muted/50"
                            >
                                <div class="flex items-center gap-3">
                                    <div
                                        class="flex h-10 w-10 items-center justify-center rounded-full bg-green-100"
                                    >
                                        <Scale
                                            class="size-5 text-green-500"
                                        />
                                    </div>
                                    <span class="font-medium">Obesidade</span>
                                </div>
                                <Toggle name="is_obese" />
                            </div>
                            <div
                                class="flex items-center justify-between p-4 transition-colors hover:bg-muted/50"
                            >
                                <div class="flex items-center gap-3">
                                    <div
                                        class="flex h-10 w-10 items-center justify-center rounded-full bg-teal-100"
                                    >
                                        <Eye
                                            class="size-5 text-teal-500"
                                        />
                                    </div>
                                    <span class="font-medium"
                                        >Exame Fundo de Olho</span
                                    >
                                </div>
                                <Toggle name="has_back_eye_exam" />
                            </div>
                        </div>

                        <div class="mt-4 grid grid-cols-2 gap-4">
                            <div class="space-y-1">
                                <Label for="blood_pressure"
                                    >Pressão Arterial</Label
                                >
                                <Input
                                    id="blood_pressure"
                                    name="blood_pressure"
                                    type="text"
                                    placeholder="12/8"
                                />
                                <InputError :message="errors.blood_pressure" />
                            </div>
                            <div class="space-y-1">
                                <Label for="blood_glucose"
                                    >Glicemia (mg/dL)</Label
                                >
                                <Input
                                    id="blood_glucose"
                                    name="blood_glucose"
                                    type="number"
                                    placeholder="90"
                                />
                                <InputError :message="errors.blood_glucose" />
                            </div>
                        </div>

                        <div class="mt-4 space-y-1">
                            <Label for="creatinine">Creatinina</Label>
                            <Input
                                id="creatinine"
                                name="creatinine"
                                type="text"
                                placeholder="0.0 mg/dL"
                            />
                            <InputError :message="errors.creatinine" />
                        </div>

                        <div
                            class="mt-4 flex gap-3 rounded-lg border border-blue-200 bg-blue-50 p-4"
                        >
                            <Info
                                class="size-4 shrink-0 text-blue-500"
                            />
                            <p
                                class="text-xs leading-relaxed text-blue-700"
                            >
                                Certifique-se de perguntar ao paciente sobre seu
                                histórico familiar para doenças cardíacas e
                                diabéticas, pois isso pode influenciar o
                                protocolo de admissão.
                            </p>
                        </div>
                    </div>

                    <div
                        class="flex flex-col-reverse gap-3 border-t border-border pt-6 sm:flex-row sm:justify-end lg:col-span-12"
                    >
                        <Button as-child variant="outline" class="w-full sm:w-auto">
                            <Link :href="index().url">Cancelar</Link>
                        </Button>
                        <Button
                            type="submit"
                            class="w-full sm:w-auto"
                            :disabled="processing"
                        >
                            <Spinner v-if="processing" />
                            <UserPlus v-else class="size-4" />
                            Cadastrar Paciente
                        </Button>
                    </div>
                </Form>
            </div>
        </div>
    </AppLayout>
</template>
