<template>
    <Head title="Pesanan" />
    <AppLayout>
        <template #title>Pesanan</template>
        <template #desc>Manage pesanan disini</template>

        <Link :href="route('dashboard.orders.create')">
            <Button>Buat pesanan</Button>
        </Link>

        <div class="my-4 bg-white shadow-md rounded-md p-6">
            <DropdownMenu>
                <DropdownMenuTrigger as-child>
                    <Button class="mb-4" variant="outline">
                        <ListFilter />
                        Filter by Status
                    </Button>
                </DropdownMenuTrigger>
                <DropdownMenuContent>
                    <DropdownMenuLabel>Status</DropdownMenuLabel>
                    <DropdownMenuSeparator />
                    <DropdownMenuItem 
                        v-for="status in statuses" 
                        :key="status"
                        @click="filterByStatus(status)"
                    >
                        {{ status }}
                    </DropdownMenuItem>
                </DropdownMenuContent>
            </DropdownMenu>
            <Table>
                <TableCaption>Daftar pesanan</TableCaption>
                <TableHeader>
                    <TableRow>
                        <TableHead>No</TableHead>
                        <TableHead>Order number</TableHead>
                        <TableHead>Nama</TableHead>
                        <TableHead>Quantity</TableHead>
                        <TableHead>Tanggal Masuk</TableHead>
                        <TableHead>Status</TableHead>
                        <TableHead>Ubah status</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="(order, index) in orders?.data" :key="order">
                        <TableCell> {{ displayNumber(orders, index) }} </TableCell>
                        <TableCell> 
                            <Link class="text-blue-500 hover:text-blue-600 underline" :href="route('dashboard.orders.show', order.id)"> {{ order.order_number }} </Link>    
                        </TableCell>
                        <TableCell> {{ order.user.name }} </TableCell>
                        <TableCell> {{ order.quantity }} </TableCell>
                        <TableCell> {{ order.pickup_date }} </TableCell>
                        <TableCell> 
                            <OrderStatus :status="order.status" /> 
                        </TableCell>
                        <TableCell>
                            <DropdownMenu>
                                <DropdownMenuTrigger as-child>
                                    <Button variant="outline">Pilih status</Button>
                                </DropdownMenuTrigger>
                                <DropdownMenuContent>
                                  <DropdownMenuLabel>Status Pesanan</DropdownMenuLabel>
                                  <DropdownMenuSeparator />
                                  <DropdownMenuItem v-for="status in statuses" :key="status"> 
                                    <Link :href="route('dashboard.orders.status', [order.id, status])"> {{ status }} </Link>
                                  </DropdownMenuItem>
                                </DropdownMenuContent>
                            </DropdownMenu>
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
import { Head, Link, router } from '@inertiajs/vue3';
import { Button } from "@/components/ui/button"
import { ListFilter } from 'lucide-vue-next';
import {
  Table,
  TableBody,
  TableCaption,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/components/ui/table'
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuLabel,
  DropdownMenuSeparator,
  DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu'
import OrderStatus from '@/components/OrderStatus.vue'
import Pagination from '@/components/Pagination.vue';

defineProps({
    orders: Object
});

const statuses = [
    'diterima', 'diproses', 'selesai', 'lunas', 'belum lunas'
];

const filterByStatus = (status: string) => {
    router.get(
        route('dashboard.orders.index'),
        { status: status },
        {
            preserveState: true,
            preserveScroll: true,
            only: ['orders'],
        }
    );
};
</script>