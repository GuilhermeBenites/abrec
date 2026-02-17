<script setup lang="ts">
import { Form, Head, Link } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import AuthBase from '@/layouts/AuthLayout.vue';
import { store } from '@/routes/login';
import { request } from '@/routes/password';

defineProps<{
    status?: string;
    canResetPassword: boolean;
    canRegister: boolean;
}>();
</script>

<template>
    <AuthBase title="Abrec - Login" description="Acesse sua conta para gerenciar pacientes">

        <Head title="Login - Abrec" />

        <div v-if="status" class="mb-4 text-center text-sm font-medium text-green-600">
            {{ status }}
        </div>

        <Form v-bind="store.form()" :reset-on-success="['password']" v-slot="{ errors, processing }" class="space-y-6">
            <div class="space-y-1.5">
                <Label for="email" class="ml-1 text-sm font-medium text-gray-700">
                    E-mail
                </Label>
                <div class="group relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-gray-400 transition-colors group-focus-within:text-red-600" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <Input id="email" type="email" name="email" required autofocus :tabindex="1" autocomplete="email"
                        placeholder="Seu e-mail"
                        class="bg-gray-50 h-auto rounded-xl border-gray-200 py-3 pl-10 text-gray-900 placeholder-gray-400 transition-all focus-visible:border-red-600 focus-visible:ring-2 focus-visible:ring-red-600/20" />
                </div>
                <InputError :message="errors.email" />
            </div>

            <div class="space-y-1.5">
                <div class="flex items-center justify-between">
                    <Label for="password" class="ml-1 text-sm font-medium text-gray-700">
                        Senha
                    </Label>
                    <Link v-if="canResetPassword" :href="request()"
                        class="text-sm text-gray-600 transition-colors hover:text-red-600" :tabindex="5">
                        Esqueci minha senha
                    </Link>
                </div>
                <div class="group relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-gray-400 transition-colors group-focus-within:text-red-600" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <Input id="password" type="password" name="password" required :tabindex="2"
                        autocomplete="current-password" placeholder="Sua senha de acesso"
                        class="bg-gray-50 h-auto rounded-xl border-gray-200 py-3 pl-10 text-gray-900 placeholder-gray-400 transition-all focus-visible:border-red-600 focus-visible:ring-2 focus-visible:ring-red-600/20" />
                </div>
                <InputError :message="errors.password" />
            </div>

            <div class="flex items-center">
                <Label for="remember" class="flex items-center gap-3 text-sm font-normal text-gray-700">
                    <Checkbox id="remember" name="remember" :tabindex="3" />
                    <span>Lembrar de mim</span>
                </Label>
            </div>

            <Button type="submit" :tabindex="4" :disabled="processing" data-test="login-button"
                class="hover:bg-red-700 mt-2 flex h-auto w-full transform items-center justify-center gap-2 rounded-xl bg-red-600 py-3.5 font-semibold text-white shadow-lg shadow-red-200 transition-all active:scale-[0.98]">
                <Spinner v-if="processing" />
                <span>{{ processing ? 'Entrando...' : 'Entrar no Sistema' }}</span>
                <svg v-if="!processing" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                </svg>
            </Button>
        </Form>
    </AuthBase>
</template>
