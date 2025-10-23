<template>
    <Head title="Update Layanan" />
    <AppLayout>
        <template #title>Update Layanan</template>
        <template #desc> Lorem ipsum, dolor sit amet consectetur adipisicing elit. Dolorem quis modi vitae. </template>

        <Card>
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
                    <Button type="submit">Update</Button>
                </div>
            </form>
        </Card>
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
import Card from '@/components/Card.vue';

const props = defineProps({
    service: Object
})

const form = useForm({
    name: props.service?.name,
    price: props.service?.price,
})

function submit() {
    form.put(route('dashboard.layanan.update', props.service?.id), {
        onSuccess: () => toast.success('Layanan berhasil diupdate')
    })
}
</script>