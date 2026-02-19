<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { Shield, Users } from 'lucide-vue-next';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import AppLogo from './AppLogo.vue';
import { index } from '@/routes/patients';
import { index as usersIndex } from '@/routes/users';

const page = usePage();
const isAdmin = computed(() => (page.props.auth as { isAdmin?: boolean })?.isAdmin ?? false);

const mainNavItems = computed<NavItem[]>(() => {
    const items: NavItem[] = [
        {
            title: 'Lista de Pacientes',
            href: index().url,
            icon: Users,
        },
    ];
    if (isAdmin.value) {
        items.push({
            title: 'Usuários',
            href: usersIndex().url,
            icon: Shield,
        });
    }
    return items;
});

</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="index().url">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
