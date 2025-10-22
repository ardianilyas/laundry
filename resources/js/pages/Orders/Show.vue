<template>
    <Head title="Detail Pesanan" />
    <AppLayout>
        <template #title>Detail Pesanan</template>
        <template #desc>Lihat detail pesanan</template>

        <div class="my-4 p-6 bg-white shadow-md rounded-md">
            <h4 class="mb-2 text-2xl font-semibold text-slate-800">
                Order Number : {{ localOrder.order_number }}
            </h4>
        
            <div class="mt-2 text-gray-600">
                Pelanggan: <b>{{ localOrder.user?.name }}</b>
            </div>
        
            <!-- Detail Layanan -->
            <div class="mt-6">
                <h5 class="text-lg font-medium mb-3 text-gray-700">Detail Layanan</h5>
        
                <div class="overflow-x-auto border rounded-md">
                    <table class="min-w-full border-collapse text-sm">
                        <thead class="bg-slate-100 text-slate-700 text-left">
                            <tr>
                                <th class="p-3 border-b">No</th>
                                <th class="p-3 border-b">Layanan</th>
                                <th class="p-3 border-b">Berat (kg)</th>
                                <th class="p-3 border-b">Harga Satuan</th>
                                <th class="p-3 border-b">Total</th>
                                <th class="p-3 border-b">Estimasi Selesai</th>
                                <th class="p-3 border-b text-center">Status Pembayaran</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="(detail, index) in localOrder.order_details"
                                :key="detail.id"
                                class="hover:bg-slate-50"
                            >
                                <td class="p-3 border-b">{{ index + 1 }}</td>
                                <td class="p-3 border-b">{{ detail.service?.name ?? '-' }}</td>
                                <td class="p-3 border-b">{{ detail.quantity }}</td>
                                <td class="p-3 border-b">{{ formatCurrency(detail.price) }}</td>
                                <td class="p-3 border-b font-semibold">
                                    {{ formatCurrency(detail.amount) }}
                                </td>
                                <td class="p-3 border-b">{{ detail.estimated_date }}</td>
                                <td class="p-3 border-b text-center">
                                    <span
                                        v-if="detail.payment_status === 'unpaid'"
                                        class="px-3 py-1 text-xs rounded-full bg-red-200 text-red-600 border border-red-300"
                                    >
                                        Belum lunas
                                    </span>
                                    <span
                                        v-else
                                        class="px-3 py-1 text-xs rounded-full bg-green-200 text-green-700 border border-green-300"
                                    >
                                        Lunas
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        
            <!-- Ringkasan Pesanan -->
            <div class="mt-8 grid grid-cols-3 gap-4">
                <div class="flex flex-col space-y-4">
                    <p>Total Berat (kg)</p>
                    <p>Total Harga</p>
                    <p>Estimasi Selesai</p>
                    <p>Status Pesanan</p>
                </div>
                <div class="flex flex-col space-y-4 col-span-2">
                    <p>{{ localOrder.quantity }}</p>
                    <p><b>{{ formatCurrency(localOrder.total_amount) }}</b></p>
                    <p>{{ localOrder.estimated_date }}</p>
                    <p>
                        <OrderStatus :status="localOrder.status" />
                    </p>
                </div>
            </div>
        
            <!-- Tombol pembayaran -->
            <div class="mt-4 flex gap-4 items-center">
                <div>
                    <Button
                        :disabled="invoiceUrl"
                        @click="pay"
                        v-if="!localOrder.invoice_url"
                    >
                        Generate Link
                    </Button>
                </div>
        
                <div v-if="invoiceUrl && localOrder.status !== 'lunas'" class="h-full flex items-center">
                    <a
                        class="inline-flex text-blue-500 hover:text-blue-600 underline"
                        :href="invoiceUrl"
                        target="_blank"
                    >
                        Payment link
                    </a>
                </div>
            </div>
        </div>        
    </AppLayout>
</template>

<script setup lang="ts">
import { onMounted, onUnmounted, reactive, ref } from 'vue'
import AppLayout from '@/layouts/AppLayout.vue';
import OrderStatus from '@/components/OrderStatus.vue';
import { Button } from "@/components/ui/button"
import { formatCurrency } from '@/helpers/helpers';
import { Head, router } from '@inertiajs/vue3';

const props = defineProps({
    order: Object,
});

const localOrder = reactive({ ...props.order });
const invoiceUrl = ref(localOrder.invoice_url ?? null);

const pay = () => {
    router.get(route('dashboard.orders.payment', localOrder.id));
}

onMounted(() => {
    if(localOrder.id) {
        window.Echo.private(`order.${localOrder.id}`)
            .listen('.order.status.updated', (e: any) => {
                if(localOrder.id === e.order_id) {
                    localOrder.status = e.status;
                }
                
                if (e.status === 'lunas') { // Parameter 'd' implicitly has an 'any' type.
                    localOrder.order_details.forEach((detail: any) => {
                    detail.payment_status = 'paid'
                    })
                }

                // update invoice url jika ada perubahan
                if (e.invoice_url) {
                    invoiceUrl.value = e.invoice_url
                }
            });
    }
})

onUnmounted(() => {
    if (localOrder.value?.id) {
        window.Echo.leave(`order.${localOrder.id}`);
    }
});
</script>