<template>
    <div class="bg-white rounded-xl p-6 flex justify-center shadow-md">
        <Line :data="chartData" :options="chartOptions" />
    </div>
</template>

<script setup lang="ts">
import { Line } from 'vue-chartjs';
import { Chart as ChartJS, Title, Tooltip, Legend, LineElement, CategoryScale, LinearScale, PointElement } from 'chart.js';

ChartJS.register(Title, Tooltip, Legend, LineElement, CategoryScale, LinearScale, PointElement);

const props = defineProps({
    monthlyTransactions: Array
});

const labels = props.monthlyTransactions?.map((item: any) => item.month) || [];
const data = props.monthlyTransactions?.map((item: any) => item.total) || [];

const chartData = {
    labels,
    datasets: [
        {
            label: 'Transaksi Bulanan',
            data, 
            borderColor: '#3b82f6',
            backgroundColor: 'rgba(59, 130, 246, 0.2)',
            tension: 0.4,
        }
    ]
}

const chartOptions = {
    responsive: true,
    plugins: {
        legend: { position: 'top' as const },
        title: {
            display: true,
            text: 'Transaksi Bulanan',
        }
    }
}
</script>