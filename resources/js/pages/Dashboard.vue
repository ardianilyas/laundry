<script setup lang="ts">
import { formatCurrency } from '@/helpers/helpers';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

defineProps({
    totalPendapatan: Number,
    totalUser: Number,
    totalTransaksi: Number,
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex min-h-screen flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                <div class="p-6 border border-slate-200 rounded-md shadow-md">
                    <h3>Total Pendapatan Bulan Ini</h3>
                    <p class="text-2xl font-semibold"> {{ formatCurrency(totalPendapatan) }} </p>
                </div>
                <div class="p-6 border border-slate-200 rounded-md shadow-md">
                    <h3>Total Transaksi</h3>
                    <p class="text-2xl font-semibold"> {{ totalTransaksi }} </p>
                </div>
                <div class="p-6 border border-slate-200 rounded-md shadow-md">
                    <h3>Total User</h3>
                    <p class="text-2xl font-semibold"> {{ totalUser }} </p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
