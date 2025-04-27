<script setup lang="ts">
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import { LayoutGrid, NotepadText, FileClock, WashingMachine } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';
import useRoles from '@/composables/useRoles';

const { hasRole } = useRoles();

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
        icon: LayoutGrid,
        isAdmin: true
    },
    {
        title: 'Layanan',
        href: '/dashboard/layanan',
        icon: WashingMachine,
        isAdmin: hasRole('admin')
    },
    {
        title: 'Riwayat Pesanan',
        href: '/dashboard/orders-history',
        icon: FileClock,
        isAdmin: true
    },
    {
        title: 'Laporan',
        href: '/dashboard/laporan',
        icon: FileClock,
        isAdmin: true
    },
    {
        title: 'Pesanan',
        href: '/dashboard/orders',
        icon: NotepadText,
        isAdmin: hasRole('admin')
    }
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="route('dashboard')">
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
