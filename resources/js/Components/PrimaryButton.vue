<script setup>
import { Store } from '@/store/index';

const colorStore = Store();
defineProps({
    type: {
        type: String,
        default: 'submit',
    },
    loading: {
        type: Boolean,
        default: false,
    },
});
</script>

<template>
    <!-- <button :type="type" class="text-center inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest hover:bg-[#1d4ed8] focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 transition ease-in-out">
        <slot />
    </button> -->
    <v-btn
        :loading="loading"
        :type="type"
        class="text-center"
        :color="colorStore.color"
        elevation="2"
    >
        <slot />
    </v-btn>
</template>
