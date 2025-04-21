<template>
    <span :class="statusClass" class="text-sm px-4 py-1 rounded-full">
        {{ status }}
    </span>
</template>

<script setup lang="ts">
import { computed } from 'vue'
const props = defineProps({
    status: {
        required: true,
        type: String,
    }
})

const statusClass = computed(() => {
    switch(props.status.toLowerCase()) {
        case 'diterima':
            return 'text-yellow-600 bg-yellow-200';
        case 'diproses':
            return 'text-blue-600 bg-blue-200';
        case 'selesai':
            return 'text-violet-600 bg-violet-200';
        case 'lunas':
            return 'text-green-700 bg-green-200';
        case 'belum lunas':
            return 'text-red-700 bg-red-200';
        default:
            return 'text-gray-600 bg-gray-200';
    }
})
</script>