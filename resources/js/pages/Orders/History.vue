<template>
    <Head title="Riwayat Pesanan" />
    <AppLayout>
        <template #title>Riwayat Pesanan</template>
        <template #desc>Daftar riwayat pesanan</template>

        <div class="my-4 p-6 bg-white shadow-md rounded-md">
            <Table>
                <TableCaption>Daftar riwayat pesanan</TableCaption>
                <TableHeader>
                    <TableRow>
                        <TableHead>No</TableHead>
                        <TableHead>Order number</TableHead>
                        <TableHead>Quantity</TableHead>
                        <TableHead>Tanggal Masuk</TableHead>
                        <TableHead class="w-3/12">Status</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="(order, index) in orders?.data" :key="order">
                        <TableCell> {{ displayNumber(orders, index) }} </TableCell>
                        <TableCell> 
                            <Link class="text-blue-500 hover:text-blue-600 underline" :href="route('dashboard.orders.show', order.id)"> {{ order.order_number }} </Link>    
                        </TableCell>
                        <TableCell> {{ order.quantity }} </TableCell>
                        <TableCell> {{ order.pickup_date }} </TableCell>
                        <TableCell> 
                            <OrderStatus :status="order.status" /> 
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
            <Pagination :data="orders" />
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { displayNumber } from '@/helpers/helpers.js'
import { Head, Link } from '@inertiajs/vue3';
import {
  Table,
  TableBody,
  TableCaption,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/components/ui/table'
import Pagination from '@/components/Pagination.vue';
import OrderStatus from '@/components/OrderStatus.vue';

defineProps({
    orders: Object
})
</script>