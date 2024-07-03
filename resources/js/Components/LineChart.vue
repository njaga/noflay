<template>
    <div class="w-full h-full">
        <ResponsiveContainer>
            <LineChart :data="chartData">
                <CartesianGrid strokeDasharray="3 3" />
                <XAxis dataKey="name" />
                <YAxis />
                <Tooltip />
                <Line type="monotone" dataKey="value" stroke="#8884d8" activeDot={{ r: 8 }} />
            </LineChart>
        </ResponsiveContainer>
    </div>
</template>

<script setup>
import { defineProps } from 'vue';
import { ResponsiveContainer, LineChart, Line, XAxis, YAxis, CartesianGrid, Tooltip } from 'recharts';

defineProps({
    chartData: {
        type: Array,
        required: true
    }
});
</script>
