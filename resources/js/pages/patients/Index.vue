<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import {
    ChevronLeft,
    ChevronRight,
    Droplet,
    Download,
    Eye,
    Filter,
    Heart,
    HeartPulse,
    Minus,
    Pencil,
    UserPlus,
    Scale,
    Search,
} from 'lucide-vue-next';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import {
    create,
    index,
    exportMethod,
} from '@/routes/patients';

const props = defineProps<{
    patients: {
        data: Array<{
            id: number;
            name: string;
            initials: string;
            cpf: string;
            date_of_birth: string;
            age: number;
            city: string;
            health_indicators: Array<{
                key: string;
                label: string;
                color: string;
            }>;
        }>;
        current_page: number;
        last_page: number;
        per_page: number;
        total: number;
        from: number | null;
        to: number | null;
        links: Array<{
            url: string | null;
            label: string;
            active: boolean;
        }>;
    };
    filters: {
        name?: string;
        cpf?: string;
        status?: string;
    };
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Lista de Pacientes', href: index().url },
];

const filterName = ref(props.filters?.name ?? '');
const filterCpf = ref(props.filters?.cpf ?? '');
const filterStatus = ref(props.filters?.status ?? '');

watch(
    () => props.filters,
    (newFilters) => {
        filterName.value = newFilters?.name ?? '';
        filterCpf.value = newFilters?.cpf ?? '';
        filterStatus.value = newFilters?.status ?? '';
    },
    { deep: true },
);

function applyFilters() {
    const query: Record<string, string> = {};
    if (filterName.value) {
        query.name = filterName.value;
    }
    if (filterCpf.value) {
        query.cpf = filterCpf.value;
    }
    if (filterStatus.value) {
        query.status = filterStatus.value;
    }

    router.get(index().url, query, {
        preserveState: true,
        replace: true,
    });
}

function exportUrl(): string {
    const query: Record<string, string> = {};
    if (filterName.value) {
        query.name = filterName.value;
    }
    if (filterCpf.value) {
        query.cpf = filterCpf.value;
    }
    if (filterStatus.value) {
        query.status = filterStatus.value;
    }

    const params = new URLSearchParams(query);

    return exportMethod().url + (params.toString() ? `?${params}` : '');
}

function goToPage(url: string | null) {
    if (url) {
        router.get(url, {}, { preserveState: true });
    }
}

const healthIndicatorIcons: Record<string, typeof Droplet> = {
    diabetic: Droplet,
    hypertensive: Heart,
    renal: HeartPulse,
    obesity: Scale,
    none: Minus,
};

const healthIndicatorBgClasses: Record<string, string> = {
    blue: 'bg-blue-100 dark:bg-blue-900/40 text-blue-500 dark:text-blue-300',
    red: 'bg-red-100 dark:bg-red-900/40 text-red-500 dark:text-red-300',
    orange: 'bg-orange-100 dark:bg-orange-900/40 text-orange-500 dark:text-orange-300',
    green: 'bg-green-100 dark:bg-green-900/40 text-green-500 dark:text-green-300',
    gray: 'bg-gray-100 dark:bg-gray-700/60 text-gray-400 dark:text-gray-500',
};

function avatarColorClass(initials: string): string {
    const hash = initials.split('').reduce((acc, c) => acc + c.charCodeAt(0), 0);
    const colors = [
        'bg-primary/10 text-primary',
        'bg-orange-100 dark:bg-orange-900/20 text-orange-600 dark:text-orange-400',
        'bg-purple-100 dark:bg-purple-900/20 text-purple-600 dark:text-purple-400',
        'bg-teal-100 dark:bg-teal-900/20 text-teal-600 dark:text-teal-400',
    ];

    return colors[hash % colors.length];
}
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Lista de Pacientes" />

        <div class="mx-auto max-w-7xl flex flex-col gap-8 py-8 px-4 sm:px-6 lg:px-8">
            <div
                class="mb-8 flex flex-col justify-between gap-4 sm:flex-row sm:items-end"
            >
                <div>
                    <h2
                        class="mb-2 text-3xl font-bold text-gray-900 dark:text-white"
                    >
                        Lista de Pacientes
                    </h2>
                    <p
                        class="text-gray-500 dark:text-gray-400"
                    >
                        Gerencie e visualize os pacientes cadastrados no sistema.
                    </p>
                </div>
                <div class="flex gap-3">
                    <a
                        :href="exportUrl()"
                        class="flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-sm transition-colors hover:bg-gray-50 dark:border-gray-600 dark:bg-surface-dark dark:text-gray-200 dark:hover:bg-gray-700"
                    >
                        <Download class="size-5" />
                        Baixar Lista
                    </a>
                    <Link
                        :href="create().url"
                        class="flex items-center gap-2 rounded-lg bg-primary px-5 py-2.5 text-sm font-medium text-white shadow-lg shadow-red-200 transition-all hover:bg-[#B91C1C] active:scale-95 dark:shadow-red-900/20"
                    >
                        <UserPlus class="size-5" />
                        Cadastrar Novo Paciente
                    </Link>
                </div>
            </div>

            <div
                class="overflow-hidden rounded-2xl border border-gray-100 shadow-sm dark:border-gray-700"
            >
                <div
                    class="border-b border-gray-100 p-6 dark:border-gray-700"
                >
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
                        <div class="md:col-span-1">
                            <label
                                class="mb-1 block text-xs font-medium text-gray-700 dark:text-gray-300"
                                for="search-name"
                            >
                                Nome do Paciente
                            </label>
                            <div class="relative">
                                <div
                                    class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3"
                                >
                                    <Search
                                        class="size-5 text-gray-400"
                                    />
                                </div>
                                <input
                                    id="search-name"
                                    v-model="filterName"
                                    type="text"
                                    placeholder="Buscar por nome..."
                                    class="w-full rounded-lg border border-gray-200 bg-gray-50 py-2 pl-10 text-sm text-gray-900 placeholder-gray-400 focus:border-primary focus:ring-primary dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:focus:border-primary dark:focus:ring-primary"
                                    @keydown.enter="applyFilters"
                                />
                            </div>
                        </div>
                        <div class="md:col-span-1">
                            <label
                                class="mb-1 block text-xs font-medium text-gray-700 dark:text-gray-300"
                                for="search-cpf"
                            >
                                CPF
                            </label>
                            <input
                                id="search-cpf"
                                v-model="filterCpf"
                                type="text"
                                placeholder="000.000.000-00"
                                class="w-full rounded-lg border border-gray-200 bg-gray-50 py-2 text-sm text-gray-900 placeholder-gray-400 focus:border-primary focus:ring-primary dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:focus:border-primary dark:focus:ring-primary"
                                @keydown.enter="applyFilters"
                            />
                        </div>
                        <div class="md:col-span-1">
                            <label
                                class="mb-1 block text-xs font-medium text-gray-700 dark:text-gray-300"
                                for="filter-status"
                            >
                                Status de Saúde
                            </label>
                            <select
                                id="filter-status"
                                v-model="filterStatus"
                                class="w-full rounded-lg border border-gray-200 bg-gray-50 py-2 text-sm text-gray-900 focus:border-primary focus:ring-primary dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:focus:border-primary dark:focus:ring-primary"
                            >
                                <option value="">Todos</option>
                                <option value="diabetic">Diabético</option>
                                <option value="hypertensive">Hipertenso</option>
                                <option value="renal">Problema Renal</option>
                                <option value="obesity">Obesidade</option>
                            </select>
                        </div>
                        <div class="flex items-end md:col-span-1">
                            <button
                                type="button"
                                class="flex w-full items-center justify-center gap-2 rounded-lg bg-gray-100 py-2 px-4 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600"
                                @click="applyFilters"
                            >
                                <Filter class="size-5" />
                                Filtrar
                            </button>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-800/50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400"
                                    scope="col"
                                >
                                    Nome
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400"
                                    scope="col"
                                >
                                    CPF
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400"
                                    scope="col"
                                >
                                    Data de Nascimento
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400"
                                    scope="col"
                                >
                                    Cidade
                                </th>
                                <th
                                    class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400"
                                    scope="col"
                                >
                                    Indicadores
                                </th>
                                <th
                                    class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400"
                                    scope="col"
                                >
                                    Ações
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-surface-dark">
                            <tr
                                v-for="patient in patients.data"
                                :key="patient.id"
                                class="transition-colors hover:bg-gray-50 dark:hover:bg-gray-800/30"
                            >
                                <td class="whitespace-nowrap px-6 py-4">
                                    <div class="flex items-center">
                                        <div
                                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full font-bold"
                                            :class="avatarColorClass(patient.initials)"
                                        >
                                            {{ patient.initials }}
                                        </div>
                                        <div class="ml-4">
                                            <div
                                                class="text-sm font-medium text-gray-900 dark:text-white"
                                            >
                                                {{ patient.name }}
                                            </div>
                                            <div
                                                class="text-xs text-gray-500 dark:text-gray-400"
                                            >
                                                ID: #{{ String(patient.id).padStart(5, '0') }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    <div
                                        class="text-sm text-gray-900 dark:text-gray-300"
                                    >
                                        {{ patient.cpf }}
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    <div
                                        class="text-sm text-gray-900 dark:text-gray-300"
                                    >
                                        {{ patient.date_of_birth }}
                                    </div>
                                    <div
                                        class="text-xs text-gray-500 dark:text-gray-400"
                                    >
                                        {{ patient.age }} anos
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    <span
                                        class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300"
                                    >
                                        {{ patient.city }}
                                    </span>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-center">
                                    <div class="flex justify-center -space-x-1">
                                        <div
                                            v-for="indicator in patient.health_indicators"
                                            :key="indicator.key"
                                            class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full border-2 border-white dark:border-surface-dark"
                                            :class="healthIndicatorBgClasses[indicator.color] ?? healthIndicatorBgClasses.gray"
                                            :title="indicator.label"
                                        >
                                            <component
                                                :is="healthIndicatorIcons[indicator.key] ?? Minus"
                                                class="size-4"
                                            />
                                        </div>
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-2">
                                        <button
                                            type="button"
                                            class="p-1 text-gray-400 transition-colors hover:text-primary"
                                            title="Editar"
                                        >
                                            <Pencil class="size-5" />
                                        </button>
                                        <button
                                            type="button"
                                            class="p-1 text-gray-400 transition-colors hover:text-primary"
                                            title="Ver Detalhes"
                                        >
                                            <Eye class="size-5" />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div
                    v-if="patients.last_page > 1"
                    class="flex items-center justify-between border-t border-gray-200 px-4 py-3 dark:border-gray-700 sm:px-6"
                >
                    <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm text-gray-700 dark:text-gray-400">
                                Mostrando
                                <span class="font-medium text-gray-900 dark:text-white">
                                    {{ patients.from ?? 0 }}
                                </span>
                                a
                                <span class="font-medium text-gray-900 dark:text-white">
                                    {{ patients.to ?? 0 }}
                                </span>
                                de
                                <span class="font-medium text-gray-900 dark:text-white">
                                    {{ patients.total }}
                                </span>
                                resultados
                            </p>
                        </div>
                        <div>
                            <nav
                                aria-label="Pagination"
                                class="isolate inline-flex -space-x-px rounded-md shadow-sm"
                            >
                                <button
                                    type="button"
                                    class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0 dark:ring-gray-600 dark:hover:bg-gray-700"
                                    :disabled="patients.current_page === 1"
                                    :aria-label="'Anterior'"
                                    @click="goToPage(patients.links[0]?.url)"
                                >
                                    <ChevronLeft class="size-5" />
                                </button>
                                <template
                                    v-for="(link, idx) in patients.links.slice(1, -1)"
                                    :key="idx"
                                >
                                    <span
                                        v-if="!link.url"
                                        class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-700 ring-1 ring-inset ring-gray-300 focus:outline-offset-0 dark:text-gray-400 dark:ring-gray-600"
                                    >
                                        {{ link.label }}
                                    </span>
                                    <button
                                        v-else
                                        type="button"
                                        class="relative inline-flex items-center px-4 py-2 text-sm font-semibold ring-1 ring-inset ring-gray-300 focus:z-20 focus:outline-offset-0 dark:ring-gray-600"
                                        :class="link.active
                                            ? 'z-10 bg-primary text-white focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary'
                                            : 'text-gray-900 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700'"
                                        @click="goToPage(link.url)"
                                    >
                                        {{ link.label }}
                                    </button>
                                </template>
                                <button
                                    type="button"
                                    class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0 dark:ring-gray-600 dark:hover:bg-gray-700"
                                    :disabled="patients.current_page === patients.last_page"
                                    :aria-label="'Próximo'"
                                    @click="goToPage(patients.links[patients.links.length - 1]?.url ?? null)"
                                >
                                    <ChevronRight class="size-5" />
                                </button>
                            </nav>
                        </div>
                    </div>
                </div>

                <div
                    v-else-if="patients.total > 0"
                    class="border-t border-gray-200 px-4 py-3 dark:border-gray-700 sm:px-6"
                >
                    <p class="text-sm text-gray-700 dark:text-gray-400">
                        Mostrando
                        <span class="font-medium text-gray-900 dark:text-white">
                            {{ patients.from }}
                        </span>
                        a
                        <span class="font-medium text-gray-900 dark:text-white">
                            {{ patients.to }}
                        </span>
                        de
                        <span class="font-medium text-gray-900 dark:text-white">
                            {{ patients.total }}
                        </span>
                        resultados
                    </p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
