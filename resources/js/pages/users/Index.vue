<script setup lang="ts">
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import { ChevronLeft, ChevronRight, Pencil, Trash2, UserPlus, Users } from 'lucide-vue-next';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { create, destroy, edit, index } from '@/routes/users';

const props = defineProps<{
    users: {
        data: Array<{
            id: number;
            name: string;
            email: string;
            role: string;
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
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Lista de Usuários', href: index().url },
];

const page = usePage();
const flash = page.props.flash as { success?: string; error?: string } | undefined;

const roleLabels: Record<string, string> = {
    admin: 'Administrador',
    user: 'Usuário',
};

function roleDisplayName(role: string): string {
    return roleLabels[role] ?? role;
}

function goToPage(url: string | null) {
    if (url) {
        router.get(url, {}, { preserveState: true });
    }
}

const userToDelete = ref<{
    id: number;
    name: string;
} | null>(null);

const isDeleteModalOpen = ref(false);

function openDeleteModal(user: { id: number; name: string }) {
    userToDelete.value = user;
    isDeleteModalOpen.value = true;
}

function closeDeleteModal() {
    userToDelete.value = null;
    isDeleteModalOpen.value = false;
}

function confirmDelete() {
    if (!userToDelete.value) return;

    const userId = userToDelete.value.id;

    router.delete(destroy.url({ user: userId }), {
        preserveScroll: true,
        onSuccess: () => closeDeleteModal(),
    });
}
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Lista de Usuários" />

        <div class="flex flex-col gap-8 py-8 px-4 sm:px-6 lg:px-8">
            <div v-if="flash?.success"
                class="rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-800">
                {{ flash.success }}
            </div>
            <div v-if="flash?.error"
                class="rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-800">
                {{ flash.error }}
            </div>
            <div class="overflow-hidden rounded-2xl border border-gray-100 shadow-sm">
                <div
                    class="flex flex-col gap-4 border-b border-gray-200 bg-red-50 p-6 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center">
                        <div
                            class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-red-100 text-primary">
                            <Users class="size-8" />
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">
                                Lista de Usuários
                            </h2>
                            <p class="text-sm text-gray-500">
                                Gerencie os usuários do sistema.
                            </p>
                        </div>
                    </div>
                    <Link :href="create().url"
                        class="flex items-center gap-2 rounded-lg bg-primary px-5 py-2 text-sm font-medium text-white shadow-lg shadow-red-200 transition-all hover:bg-[#B91C1C] active:scale-95">
                        <UserPlus class="size-5" />
                        Cadastrar Novo Usuário
                    </Link>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50/50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                                    scope="col">
                                    Nome
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                                    scope="col">
                                    E-mail
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                                    scope="col">
                                    Perfil
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500"
                                    scope="col">
                                    Ações
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            <tr v-for="user in users.data" :key="user.id"
                                class="transition-colors hover:bg-gray-50">
                                <td class="whitespace-nowrap px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ user.name }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        ID: #{{ String(user.id).padStart(5, '0') }}
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    <div class="text-sm text-gray-900">
                                        {{ user.email }}
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    <span
                                        class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ roleDisplayName(user.role) }}
                                    </span>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-2">
                                        <Link :href="edit({ user: user.id }).url"
                                            class="p-1 text-gray-400 transition-colors hover:text-primary"
                                            title="Editar">
                                            <Pencil class="size-5" />
                                        </Link>
                                        <button type="button"
                                            class="p-1 text-gray-400 transition-colors hover:text-red-600"
                                            title="Excluir"
                                            @click="openDeleteModal({ id: user.id, name: user.name })">
                                            <Trash2 class="size-5" />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div v-if="users.last_page > 1"
                    class="flex items-center justify-between border-t border-gray-200 px-4 py-3 sm:px-6">
                    <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm text-gray-700">
                                Mostrando
                                <span class="font-medium text-gray-900">
                                    {{ users.from ?? 0 }}
                                </span>
                                a
                                <span class="font-medium text-gray-900">
                                    {{ users.to ?? 0 }}
                                </span>
                                de
                                <span class="font-medium text-gray-900">
                                    {{ users.total }}
                                </span>
                                resultados
                            </p>
                        </div>
                        <div>
                            <nav aria-label="Pagination" class="isolate inline-flex -space-x-px rounded-md shadow-sm">
                                <button type="button"
                                    class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0"
                                    :disabled="users.current_page === 1" :aria-label="'Anterior'"
                                    @click="goToPage(users.links[0]?.url ?? null)">
                                    <ChevronLeft class="size-5" />
                                </button>
                                <template v-for="(link, idx) in users.links.slice(1, -1)" :key="idx">
                                    <span v-if="!link.url"
                                        class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-700 ring-1 ring-inset ring-gray-300 focus:outline-offset-0">
                                        {{ link.label }}
                                    </span>
                                    <button v-else type="button"
                                        class="relative inline-flex items-center px-4 py-2 text-sm font-semibold ring-1 ring-inset ring-gray-300 focus:z-20 focus:outline-offset-0"
                                        :class="link.active
                                            ? 'z-10 bg-primary text-white focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary'
                                            : 'text-gray-900 hover:bg-gray-50'" @click="goToPage(link.url)">
                                        {{ link.label }}
                                    </button>
                                </template>
                                <button type="button"
                                    class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0"
                                    :disabled="users.current_page === users.last_page" :aria-label="'Próximo'"
                                    @click="goToPage(users.links[users.links.length - 1]?.url ?? null)">
                                    <ChevronRight class="size-5" />
                                </button>
                            </nav>
                        </div>
                    </div>
                </div>

                <div v-else-if="users.total > 0" class="border-t border-gray-200 px-4 py-3 sm:px-6">
                    <p class="text-sm text-gray-700">
                        Mostrando
                        <span class="font-medium text-gray-900">
                            {{ users.from }}
                        </span>
                        a
                        <span class="font-medium text-gray-900">
                            {{ users.to }}
                        </span>
                        de
                        <span class="font-medium text-gray-900">
                            {{ users.total }}
                        </span>
                        resultados
                    </p>
                </div>
            </div>

            <Dialog v-model:open="isDeleteModalOpen"
                @update:open="(v) => { if (v === false) userToDelete = null }">
                <DialogContent :show-close-button="true">
                    <DialogHeader>
                        <DialogTitle>Excluir usuário</DialogTitle>
                        <DialogDescription>
                            Tem certeza que deseja excluir
                            <strong>{{ userToDelete?.name }}</strong>? Esta ação não pode ser desfeita.
                        </DialogDescription>
                    </DialogHeader>
                    <DialogFooter class="gap-2 sm:gap-0">
                        <DialogClose as-child>
                            <Button variant="secondary">Cancelar</Button>
                        </DialogClose>
                        <Button variant="destructive" @click="confirmDelete">
                            Excluir
                        </Button>
                    </DialogFooter>
                </DialogContent>
            </Dialog>
        </div>
    </AppLayout>
</template>
