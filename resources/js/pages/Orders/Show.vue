<template>
    <Head title="Detail Pesanan" />
    <AppLayout>
        <template #title>Detail Pesanan</template>
        <template #desc>Lihat detail pesanan</template>

        <div class="my-4 p-6 bg-white shadow-md rounded-md">
            <h4 class="mb-2 text-2xl font-semibold text-slate-800">Order Number : {{ order?.order_number }} </h4>

            <div class="mt-8 grid grid-cols-3 gap-4">
                <div class="flex flex-col space-y-4">
                    <p>Berat (kg)</p>
                    <p>Total harga</p>
                    <p>Estimasi selesai</p>
                    <p>Status pesanan</p>
                    <p>Status pembayaran</p>
                </div>
                <div class="flex flex-col space-y-4">
                    <p> {{ order?.quantity }} </p>
                    <p> <b>{{ formatCurrency(order?.order_detail.amount) }}</b> </p>
                    <p> {{ order?.estimated_date }} </p>
                    <p> 
                        <OrderStatus :status="order?.status" /> 
                    </p>
                    <p>
                        <span class="px-4 py-1 text-sm rounded-full bg-red-200 text-red-600 border border-red-300" v-if="order?.order_detail.payment_status === 'unpaid'">Belum lunas</span>
                        <span class="px-4 py-1 text-sm rounded-full bg-green-200 text-green-700 border border-green-300" v-else>Lunas</span>
                    </p>
                </div>
            </div>
            <Button class="mt-4" v-if="order?.order_detail.payment_status === 'unpaid'">Bayar Online</Button>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import { onMounted, onUnmounted, ref } from 'vue'
import AppLayout from '@/layouts/AppLayout.vue';
import OrderStatus from '@/components/OrderStatus.vue';
import { Button } from "@/components/ui/button"
import { formatCurrency } from '@/helpers/helpers';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    order: Object,
});

const localOrder = ref(props.order);

onMounted(() => {
    if(localOrder?.value.id) {
        window.Echo.private(`order.${localOrder.value.id}`)
            .listen('.order.status.updated', (e: any) => {
                if (localOrder.value) {
                    localOrder.value.status = e.status;
            }
        });
    }
})

onUnmounted(() => {
    if (localOrder.value?.id) {
        window.Echo.leave(`order.${localOrder.value.id}`);
    }
});
</script>