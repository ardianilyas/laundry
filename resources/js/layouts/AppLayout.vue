<script setup lang="ts">
import { Toaster } from '@/components/ui/sonner'
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';
import type { BreadcrumbItemType } from '@/types';

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
}

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});
</script>

<template>
    <Toaster richColors position="top-right" closeButton />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-8">
            <h2 class="text-2xl font-semibold text-slate-800">
                <slot name="title" />
            </h2>
            <p class="text-slate-500 leading-tight">
                <slot name="desc" />
            </p>
            <div class="py-6">
                <slot />
            </div>
        </div>
    </AppLayout>
</template>
