<template>
    <Head title="Layanan" />
    <AppLayout>
        <template #title>Daftar Layanan</template>
        <template #desc> Lorem ipsum dolor sit amet consectetur adipisicing elit. Sunt quisquam nulla ad? </template>

        <Link :href="route('dashboard.layanan.create')">
            <Button>Create New Layanan</Button>
        </Link>

        <Card>
            <Table>
                <TableCaption>List layanan yang tersedia</TableCaption>
                <TableHeader>
                    <TableRow>
                        <TableHead>No</TableHead>
                        <TableHead>Nama</TableHead>
                        <TableHead>Harga</TableHead>
                        <TableHead>Action</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="(service, index) in services" :key="service">
                        <TableCell> {{ index + 1 }} </TableCell>
                        <TableCell> {{ service.name }} </TableCell>
                        <TableCell> {{ formatCurrency(service.price) }} </TableCell>
                        <TableCell class="flex gap-3">
                            <Link class="text-yellow-500" :href="route('dashboard.layanan.edit', service.id)">Edit</Link>
                            <button class="text-red-500" @click.prevent="deleteService(service.id)">Delete</button>
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </Card>
    </AppLayout>
</template>

<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { formatCurrency } from '@/helpers/helpers';
import { Button } from "@/components/ui/button"
import {
  Table,
  TableBody,
  TableCaption,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from "@/components/ui/table"
import { toast } from 'vue-sonner';
import Card from '@/components/Card.vue';

defineProps({
    services: Object
});

const form = useForm({});

function deleteService(id: number) {
    form.delete(route('dashboard.layanan.destroy', id), {
        onSuccess: () => toast.success('Layanan berhasil dihapus')
    })
}
</script>