<template>
    <div>
        <Link :href="route('register')" class="text-sm text-blue-600 hover:text-blue-400 border-hidden">
            <span class="ms-2 text-sm">register</span>
        </Link>
        <Link :href="route('login')" class="text-sm text-blue-600 hover:text-blue-400 border-hidden">
            <span class="ms-2 text-sm">login</span>
        </Link>
        <div>
        <button @click="fetchDashboardData">Get Dashboard Data</button>
        <div v-if="dashboardData">
            <!-- Display the data -->
            <pre>{{ dashboardData }}</pre>
        </div>
    </div>
    </div>
</template>
<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref, watch, onMounted } from 'vue';
import axios from 'axios';

const dashboardData = ref(null);

const fetchDashboardData = async () => {
    try {
        const response = await axios.get('getDashboard');
        dashboardData.value = response.data;
    } catch (error) {
        console.error('Error fetching data', error);
    }
};

</script>
