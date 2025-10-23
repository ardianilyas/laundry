<template>
    <Head title="Users" />
    <AppLayout>
        <template #title>Kelola User</template>
        <template #desc>Buat akun pelanggan baru disini.</template>

        <div class="">
            <Link :href="route('dashboard.users.create')">
                <Button>Tambah User</Button>
            </Link>
        </div>

        <Card>
            <Table>
                <TableCaption>Daftar User</TableCaption>
                <TableHeader>
                    <TableRow>
                        <TableHead>No</TableHead>
                        <TableHead>Username</TableHead>
                        <TableHead>Email</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="(user, index) in users" :key="user.id">
                        <TableCell> {{ index + 1 }} </TableCell>
                        <TableCell> {{ user.name }} </TableCell>
                        <TableCell> {{ user.email }} </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </Card>

    </AppLayout>
</template>

<script setup lang="ts">
import Card from '@/components/Card.vue';
import Button from '@/components/ui/button/Button.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { Table, TableBody, TableCaption, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';

defineProps({
    users: Object,
});

</script>