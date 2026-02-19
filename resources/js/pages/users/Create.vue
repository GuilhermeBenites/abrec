<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import { User, UserPlus, Users } from 'lucide-vue-next';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { create, index, store } from '@/routes/users';

const props = defineProps<{
    roles: Array<{ value: string; label: string }>;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Lista de Usuários', href: index().url },
    { title: 'Cadastrar Usuário', href: create().url },
];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Cadastrar Usuário" />

        <div class="flex flex-col gap-8 py-8 px-4 sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-2xl border border-border shadow-sm">
                <div
                    class="flex flex-col gap-4 border-b border-border bg-red-50 p-6 sm:flex-row sm:items-center">
                    <div
                        class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-red-100 text-primary">
                        <Users class="size-8" />
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-foreground">
                            Cadastrar Usuário
                        </h3>
                        <p class="text-sm text-muted-foreground">
                            Preencha os dados do usuário abaixo.
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
                                class="text-xs font-bold uppercase tracking-wider text-primary">
                                Dados do Usuário
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

                        <div class="space-y-1">
                            <Label for="email">E-mail</Label>
                            <Input
                                id="email"
                                name="email"
                                type="email"
                                required
                                placeholder="email@exemplo.com"
                            />
                            <InputError :message="errors.email" />
                        </div>

                        <div class="space-y-1">
                            <Label for="password">Senha</Label>
                            <Input
                                id="password"
                                name="password"
                                type="password"
                                required
                                placeholder="••••••••"
                            />
                            <InputError :message="errors.password" />
                        </div>

                        <div class="space-y-1">
                            <Label for="password_confirmation">Confirmar Senha</Label>
                            <Input
                                id="password_confirmation"
                                name="password_confirmation"
                                type="password"
                                required
                                placeholder="••••••••"
                            />
                        </div>

                        <div class="space-y-1">
                            <Label for="role">Perfil</Label>
                            <select
                                id="role"
                                name="role"
                                required
                                class="border-input h-9 w-full min-w-0 rounded-md border bg-transparent px-3 py-1 text-base shadow-xs transition-[color,box-shadow] outline-none focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px] md:text-sm"
                            >
                                <option value="">Selecione um perfil</option>
                                <option
                                    v-for="role in roles"
                                    :key="role.value"
                                    :value="role.value"
                                >
                                    {{ role.label }}
                                </option>
                            </select>
                            <InputError :message="errors.role" />
                        </div>
                    </div>

                    <div
                        class="flex justify-end border-t border-border pt-6 lg:col-span-12">
                        <Button
                            type="submit"
                            class="w-full sm:w-auto"
                            :disabled="processing"
                        >
                            <Spinner v-if="processing" />
                            <UserPlus v-else class="size-4" />
                            Cadastrar Usuário
                        </Button>
                    </div>
                </Form>
            </div>
        </div>
    </AppLayout>
</template>
