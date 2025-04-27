<template>
    <Head title="Laporan" />
    <AppLayout>
        <template #title>Laporan Order {{ formatMonth(selectedMonth as string) }} </template>
        <template #desc> Lorem ipsum dolor sit amet consectetur, adipisicing elit. Nihil neque ratione deleniti. </template>

        <h3 class="text-xl font-semibold mb-3">Total pendapatan : {{ formatCurrency(totalAmount) }} </h3>

        <div class="max-w-sm">
            <Select v-model="selectedMonth" @update:modelValue="filterOrders">
                <SelectTrigger> {{ formatMonth(selectedMonth as string) }} </SelectTrigger>
                <SelectContent> 
                    <SelectItem v-for="month in availableMonth" :key="month" :value="month"> {{ formatMonth(month) }} </SelectItem>
                </SelectContent>
            </Select>
        </div>

        <div class="my-4 bg-white shadow-md rounded-md p-6">
            <Table>
                <TableCaption>Laporan order bulanan</TableCaption>
                <TableHeader>
                    <TableRow>
                        <TableHead>No</TableHead>
                        <TableHead>Order Number</TableHead>
                        <TableHead>Nama</TableHead>
                        <TableHead>Quantity</TableHead>
                        <TableHead>Harga</TableHead>
                        <TableHead>Tanggal Masuk</TableHead>
                        <TableHead>Estimasi Selesai</TableHead>
                        <TableHead class="w-1/6">Status</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="(order, index) in orders" :key="order">
                        <TableCell> {{ index + 1 }} </TableCell>
                        <TableCell> {{ order.order_number }} </TableCell>
                        <TableCell> {{ order.user.name }} </TableCell>
                        <TableCell> {{ order.quantity }} </TableCell>
                        <TableCell> {{ formatCurrency(order.order_detail.amount) }} </TableCell>
                        <TableCell> {{ order.pickup_date }} </TableCell>
                        <TableCell> {{ order.estimated_date }} </TableCell>
                        <TableCell>
                            <OrderStatus :status="order.status" />
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import OrderStatus from '@/components/OrderStatus.vue';
import {
  Table,
  TableBody,
  TableCaption,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from "@/components/ui/table"
import {
  Select,
  SelectValue,
  SelectContent,
  SelectItem,
  SelectTrigger,
} from "@/components/ui/select"

import { formatCurrency } from '@/helpers/helpers';
import { ref } from 'vue';

const props = defineProps({
    orders: Object,
    availableMonth: Array,
    selectedMonth: String,
    totalAmount: Number,
});

const selectedMonth = ref(props.selectedMonth);
console.log(selectedMonth.value);

const formatMonth = (month: string) => {
  if (!month) return '';
  const date = new Date(`${month}-01`);
  return date.toLocaleString('id-ID', { month: 'long', year: 'numeric' });
};

const filterOrders = () => {
    router.get(
        route('dashboard.laporan'),
        { month: selectedMonth.value },
        { 
            only: ['orders', 'totalAmount'],
            preserveState: true, 
            preserveScroll: true
        }
    );
};
</script>