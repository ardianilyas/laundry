<template>
    <Head title="Create User" />
    <AppLayout>
        <template #title>Form Buat Akun</template>
        <template #desc>Buat akun pelanggan baru disini</template>

        <Card>
            <form @submit.prevent="submit" class="[&>div]:mb-3 max-w-xl">
                <div>
                    <Label>Username</Label>
                    <Input v-model="form.name" type="text" placeholder="username" />
                    <InputError :message="form.errors.name" />
                </div>
                <div>
                    <Label>Email</Label>
                    <Input v-model="form.email" type="email" placeholder="email@mail.com" />
                    <InputError :message="form.errors.email" />
                </div>
                <div>
                    <Label>No. Whatsapp</Label>
                    <Input v-model="form.phone" type="text" placeholder="628xxxxxxxxx" />
                    <InputError :message="form.errors.phone" />
                </div>
                <div>
                    <Button :disabled="form.processing">
                        {{ form.processing ? 'Menyimpan' : 'Simpan' }}
                    </Button>
                </div>
            </form>
        </Card>
    </AppLayout>
</template>

<script setup lang="ts">
import Card from '@/components/Card.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Button } from '@/components/ui/button';
import { toast } from 'vue-sonner';
import InputError from '@/components/InputError.vue';

const form = useForm({
    name: '',
    email: '',
    phone: '',
});

const submit = () => {
    form.post(route('dashboard.users.store'), {
        onSuccess: () => toast.success('Akun berhasil dibuat'),
    });
}

</script>