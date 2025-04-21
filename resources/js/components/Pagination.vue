<template>
    <div v-if="data?.last_page > 1" class="my-4 flex justify-center items-center">
        <div v-if="data?.data.length > 0" class="flex justify-center items-center gap-4">
            <Link v-if="data?.prev_page_url !== null" :href="data?.prev_page_url">
                <Button :disabled="data?.prev_page_url === null" variant="outline">Prev</Button>
            </Link>
            <p> <b>{{ data?.current_page }}</b> / {{ data?.last_page }} </p>
            <Link v-if="data?.next_page_url !== null" :href="data?.next_page_url">
                <Button :disabled="data?.next_page_url === null" variant="outline">Next</Button>
            </Link>
        </div>
    </div>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import { Button } from "@/components/ui/button"
defineProps({
    data: Object
})
</script>