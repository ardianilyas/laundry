<template>
    <Head title="Buat Pesanan" />
    <AppLayout>
        <template #title>Buat Pesanan</template>
        <template #desc>Buat pesanan dengan beberapa layanan</template>

        <Card>
            <form @submit.prevent="submit" class="max-w-2xl space-y-4">
                <!-- Pelanggan -->
                <div>
                    <Label>Nama Pelanggan</Label>
                    <Select v-model="form.user_id">
                        <SelectTrigger>
                            <SelectValue placeholder="Pilih pelanggan" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem
                                v-for="user in users"
                                :key="user.id"
                                :value="user.id"
                            >
                                {{ user.name }}
                            </SelectItem>
                        </SelectContent>
                    </Select>
                    <InputError :message="form.errors.user_id" />
                </div>

                <!-- Daftar layanan -->
                <div>
                    <Label>Layanan</Label>

                    <div
                        v-for="(item, index) in form.services"
                        :key="index"
                        class="border rounded-md p-4 mb-2 space-y-2"
                    >
                        <div class="flex justify-between items-center">
                            <h4 class="font-medium">Layanan {{ index + 1 }}</h4>
                            <button
                                type="button"
                                class="text-red-500 text-sm"
                                @click="removeService(index)"
                                v-if="form.services.length > 1"
                            >
                                Hapus
                            </button>
                        </div>

                        <div>
                            <Label>Layanan</Label>
                            <Select v-model="item.service_id">
                                <SelectTrigger>
                                    <SelectValue placeholder="Pilih layanan" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem
                                        v-for="service in services"
                                        :key="service.id"
                                        :value="service.id"
                                    >
                                        {{ service.name }}
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                            <InputError
                                :message="form.errors[`services.${index}.service_id` as keyof typeof form.errors]"
                            />
                        </div>

                        <div>
                            <Label>Berat (kg)</Label>
                            <Input v-model="item.quantity" placeholder="Berat" />
                            <InputError
                                :message="form.errors[`services.${index}.quantity` as keyof typeof form.errors]"
                            />
                        </div>
                    </div>

                    <Button
                        type="button"
                        variant="outline"
                        class="mt-2"
                        @click="addService"
                    >
                        + Tambah Layanan
                    </Button>
                </div>

                <div>
                    <Label>Estimasi Hari</Label>
                    <Input
                        v-model="form.estimated_date"
                        type="number"
                        placeholder="Estimasi hari"
                    />
                    <InputError :message="form.errors.estimated_date" />
                </div>

                <!-- Tombol submit -->
                <div>
                    <Button :disabled="form.processing" type="submit">
                        Simpan Pesanan
                    </Button>
                </div>
            </form>
        </Card>
    </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import { Input } from "@/components/ui/input"
import { Label } from "@/components/ui/label"
import { Button } from "@/components/ui/button"
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select"
import { toast } from 'vue-sonner';
import Card from '@/components/Card.vue';

defineProps({
    users: Object,
    services: Object,
})

interface ServiceItem {
  service_id: number | string;
  quantity: number | string;
}

interface FormValues extends Record<string, any> {
  user_id: number | string;
  estimated_date: number | string;
  services: ServiceItem[];
}

const form = useForm<FormValues>({
  user_id: '',
  estimated_date: '',
  services: [
    { service_id: '', quantity: '' }
  ],
})

function addService() {
  form.services.push({ service_id: '', quantity: '' })
}

function removeService(index: number) {
  form.services.splice(index, 1)
}

function submit() {    
  form.post(route('dashboard.orders.store'), {
    onSuccess: () => {
      toast.success("Pesanan berhasil dibuat")
      form.reset()
    },
  })
}
</script>