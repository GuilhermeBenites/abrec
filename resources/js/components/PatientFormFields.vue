<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import {
    Activity,
    Droplet,
    Eye,
    Heart,
    HeartPulse,
    Info,
    Phone,
    Scale,
    User,
    Users,
} from 'lucide-vue-next';
import { computed, reactive, ref } from 'vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { Toggle } from '@/components/ui/toggle';
import { index } from '@/routes/patients';

const props = defineProps<{
    errors: Record<string, string>;
    processing: boolean;
    submitLabel: string;
    patient?: {
        name?: string;
        cpf?: string;
        date_of_birth?: string;
        gender?: string;
        address?: string;
        neighborhood?: string;
        city?: string;
        phone?: string | null;
        weight?: string;
        height?: string;
        blood_pressure?: string;
        blood_glucose?: string;
        creatinine?: string;
        is_diabetic?: boolean;
        is_hypertensive?: boolean;
        has_kidney_problem?: boolean;
        has_family_drc?: boolean;
        is_obese?: boolean;
        has_back_eye_exam?: boolean;
    };
}>();

function formatCpf(raw: string): string {
    const d = raw.replace(/\D/g, '').slice(0, 11);
    if (d.length <= 3) return d;
    if (d.length <= 6) return `${d.slice(0, 3)}.${d.slice(3)}`;
    if (d.length <= 9) return `${d.slice(0, 3)}.${d.slice(3, 6)}.${d.slice(6)}`;
    return `${d.slice(0, 3)}.${d.slice(3, 6)}.${d.slice(6, 9)}-${d.slice(9)}`;
}

function formatPhone(raw: string): string {
    const d = raw.replace(/\D/g, '').slice(0, 11);
    if (d.length === 0) return '';
    if (d.length <= 2) return `(${d}`;
    if (d.length <= 6) return `(${d.slice(0, 2)}) ${d.slice(2)}`;
    if (d.length <= 10) return `(${d.slice(0, 2)}) ${d.slice(2, 6)}-${d.slice(6)}`;
    return `(${d.slice(0, 2)}) ${d.slice(2, 7)}-${d.slice(7)}`;
}

const _cpfRaw = ref(formatCpf(props.patient?.cpf ?? ''));
const cpfValue = computed({
    get: () => _cpfRaw.value,
    set: (val: string | number) => {
        _cpfRaw.value = formatCpf(String(val));
    },
});

const _phoneRaw = ref(formatPhone(props.patient?.phone ?? ''));
const phoneValue = computed({
    get: () => _phoneRaw.value,
    set: (val: string | number) => {
        _phoneRaw.value = formatPhone(String(val));
    },
});

const toggles = reactive({
    is_diabetic: props.patient?.is_diabetic ?? false,
    is_hypertensive: props.patient?.is_hypertensive ?? false,
    has_kidney_problem: props.patient?.has_kidney_problem ?? false,
    has_family_drc: props.patient?.has_family_drc ?? false,
    is_obese: props.patient?.is_obese ?? false,
    has_back_eye_exam: props.patient?.has_back_eye_exam ?? false,
});
</script>

<template>
    <div class="grid grid-cols-1 gap-8 p-6 lg:grid-cols-12 lg:gap-12 lg:p-8 bg-white">
        <div class="space-y-6 lg:col-span-7">
            <div class="mb-4 flex items-center gap-2">
                <User class="size-4 text-primary" />
                <span class="text-xs font-bold uppercase tracking-wider text-primary">
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
                    :default-value="patient?.name"
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
                        :default-value="patient?.date_of_birth"
                    />
                    <InputError :message="errors.date_of_birth" />
                </div>
                <div class="space-y-2">
                    <Label>Sexo</Label>
                    <div class="flex h-[42px] rounded-lg border border-input bg-muted/50 p-1">
                        <input
                            id="gender-male"
                            name="gender"
                            type="radio"
                            value="male"
                            :checked="patient?.gender === 'male'"
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
                            :checked="patient?.gender === 'female'"
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

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div class="space-y-1">
                    <Label for="cpf">CPF</Label>
                    <Input
                        id="cpf"
                        name="cpf"
                        type="text"
                        required
                        v-model="cpfValue"
                        placeholder="000.000.000-00"
                        maxlength="14"
                        inputmode="numeric"
                    />
                    <InputError :message="errors.cpf" />
                </div>
                <div class="space-y-1">
                    <Label for="phone">
                        <Phone class="inline size-3.5 mr-1" />
                        Telefone
                        <span class="text-xs text-muted-foreground">(opcional)</span>
                    </Label>
                    <Input
                        id="phone"
                        name="phone"
                        type="text"
                        v-model="phoneValue"
                        placeholder="(00) 00000-0000"
                        maxlength="15"
                        inputmode="tel"
                    />
                    <InputError :message="errors.phone" />
                </div>
            </div>

            <div class="space-y-1">
                <Label for="address">Endereço</Label>
                <Input
                    id="address"
                    name="address"
                    type="text"
                    required
                    :default-value="patient?.address"
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
                        :default-value="patient?.neighborhood"
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
                        :default-value="patient?.city"
                        placeholder="Cidade"
                    />
                    <InputError :message="errors.city" />
                </div>
            </div>
        </div>

        <div class="space-y-6 lg:col-span-5">
            <div class="mb-4 flex items-center gap-2">
                <Activity class="size-4 text-primary" />
                <span class="text-xs font-bold uppercase tracking-wider text-primary">
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
                        :default-value="patient?.weight"
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
                        :default-value="patient?.height"
                        placeholder="000"
                    />
                    <InputError :message="errors.height" />
                </div>
            </div>

            <div class="divide-y divide-border overflow-hidden rounded-xl border border-border bg-muted/30">
                <div class="flex items-center justify-between p-4 transition-colors hover:bg-muted/50">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-100">
                            <Droplet class="size-5 text-blue-500" />
                        </div>
                        <span class="font-medium">Diabético</span>
                    </div>
                    <Toggle name="is_diabetic" v-model="toggles.is_diabetic" />
                </div>
                <div class="flex items-center justify-between p-4 transition-colors hover:bg-muted/50">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-red-100">
                            <Heart class="size-5 text-red-500" />
                        </div>
                        <span class="font-medium">Hipertenso</span>
                    </div>
                    <Toggle name="is_hypertensive" v-model="toggles.is_hypertensive" />
                </div>
                <div class="flex items-center justify-between p-4 transition-colors hover:bg-muted/50">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-orange-100">
                            <HeartPulse class="size-5 text-orange-500" />
                        </div>
                        <span class="font-medium">Problema Renal</span>
                    </div>
                    <Toggle name="has_kidney_problem" v-model="toggles.has_kidney_problem" />
                </div>
                <div class="flex items-center justify-between p-4 transition-colors hover:bg-muted/50">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-yellow-100">
                            <Users class="size-5 text-yellow-600" />
                        </div>
                        <span class="font-medium">D.R.C na Família</span>
                    </div>
                    <Toggle name="has_family_drc" v-model="toggles.has_family_drc" />
                </div>
                <div class="flex items-center justify-between p-4 transition-colors hover:bg-muted/50">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-green-100">
                            <Scale class="size-5 text-green-500" />
                        </div>
                        <span class="font-medium">Obesidade</span>
                    </div>
                    <Toggle name="is_obese" v-model="toggles.is_obese" />
                </div>
                <div class="flex items-center justify-between p-4 transition-colors hover:bg-muted/50">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-teal-100">
                            <Eye class="size-5 text-teal-500" />
                        </div>
                        <span class="font-medium">Exame Fundo de Olho</span>
                    </div>
                    <Toggle name="has_back_eye_exam" v-model="toggles.has_back_eye_exam" />
                </div>
            </div>

            <div class="mt-4 grid grid-cols-2 gap-4">
                <div class="space-y-1">
                    <Label for="blood_pressure">Pressão Arterial</Label>
                    <Input
                        id="blood_pressure"
                        name="blood_pressure"
                        type="text"
                        :default-value="patient?.blood_pressure"
                        placeholder="12/8"
                    />
                    <InputError :message="errors.blood_pressure" />
                </div>
                <div class="space-y-1">
                    <Label for="blood_glucose">Glicemia (mg/dL)</Label>
                    <Input
                        id="blood_glucose"
                        name="blood_glucose"
                        type="number"
                        :default-value="patient?.blood_glucose"
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
                    :default-value="patient?.creatinine"
                    placeholder="0.0 mg/dL"
                />
                <InputError :message="errors.creatinine" />
            </div>

            <div class="mt-4 flex gap-3 rounded-lg border border-blue-200 bg-blue-50 p-4">
                <Info class="size-4 shrink-0 text-blue-500" />
                <p class="text-xs leading-relaxed text-blue-700">
                    Certifique-se de perguntar ao paciente sobre seu
                    histórico familiar para doenças cardíacas e
                    diabéticas, pois isso pode influenciar o
                    protocolo de admissão.
                </p>
            </div>
        </div>

        <div class="flex flex-col-reverse gap-3 border-t border-border pt-6 sm:flex-row sm:justify-end lg:col-span-12">
            <Button as-child variant="outline" class="w-full sm:w-auto">
                <Link :href="index().url">Cancelar</Link>
            </Button>
            <Button type="submit" class="w-full sm:w-auto" :disabled="processing">
                <Spinner v-if="processing" />
                <slot v-else name="submit-icon" />
                {{ submitLabel }}
            </Button>
        </div>
    </div>
</template>
