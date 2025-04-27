<template>
    <Head title="Create Layanan" />
    <AppLayout>
        <template #title>Create Layanan</template>
        <template #desc> Lorem ipsum, dolor sit amet consectetur adipisicing elit. Dolorem quis modi vitae. </template>

        <div class="my-4 bg-white shadow-md rounded-md p-6">
            <form @submit.prevent="submit" class="max-w-2xl [&>div]:mb-3">
                <div>
                    <Label>Nama</Label>
                    <Input v-model="form.name" type="text" placeholder="Nama Layanan" />
                    <InputError :message="form.errors.name" />
                </div>
                <div>
                    <Label>Harga</Label>
                    <Input v-model="form.price" type="number" placeholder="Nama Layanan" />
                    <InputError :message="form.errors.price" />
                </div>
                <div>
                    <Button type="submit">Save</Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Input } from "@/components/ui/input"
import { Label } from "@/components/ui/label"
import { Button } from "@/components/ui/button"
import InputError from '@/components/InputError.vue';
import { toast } from 'vue-sonner';

const form = useForm({
    name: '',
    price: 0,
})

function submit() {
    form.post(route('dashboard.layanan.store'), {
        onSuccess: () => toast.success('Layanan berhasil ditambahkan')
    })
}
</script>