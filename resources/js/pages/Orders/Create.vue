<template>
    <Head title="Buat Pesanan" />
    <AppLayout>
        <template #title>Buat Pesanan</template>
        <template #desc>Buat pesanan disini</template>

        <div class="my-4 bg-white shadow-md rounded-md p-6">
            <form @submit.prevent="submit" class="max-w-xl [&>div]:mb-2">
                <div>
                    <Label>Nama Pelanggan</Label>
                    <Select v-model="form.user_id">
                        <SelectTrigger>
                            <SelectValue placeholder="Nama pelanggan" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem v-for="user in users" :key="user" :value="user.id"> {{ user.name }} </SelectItem>
                        </SelectContent>
                    </Select>
                    <InputError :message="form.errors.user_id" />
                </div>
                <div>
                    <Label>Layanan</Label>
                    <Select v-model="form.service_id">
                        <SelectTrigger>
                            <SelectValue placeholder="Layanan" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem v-for="service in services" :key="service" :value="service.id"> {{ service.name }} </SelectItem>
                        </SelectContent>
                    </Select>
                    <InputError :message="form.errors.service_id" />
                </div>
                <div>
                    <Label>Berat (kg)</Label>
                    <Input v-model="form.quantity" placeholder="Berat" />
                    <InputError :message="form.errors.quantity" />
                </div>
                <div>
                    <Label>Estimasi hari</Label>
                    <Input v-model="form.estimated_date" type="number" placeholder="Estimasi hari" />
                    <InputError :message="form.errors.estimated_date" />
                </div>
                <div>
                    <Button :disabled="form.processing" type="submit">Simpan</Button>
                </div>
            </form>
        </div>
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

defineProps({
    users: Object,
    services: Object,
})

interface FormValues {
  user_id: number | string;
  service_id: number | string;
  quantity: number | string;
  estimated_date: number | string;
  [key: string]: number | string;
}

const form = useForm<FormValues>({
    user_id: '',
    service_id: '',
    quantity: '',
    estimated_date: ''
})

function submit () {
    form.post(route('dashboard.orders.store'), {
        onSuccess: () => toast.success("Pesanan berhasil dibuat")
    })
}

</script>