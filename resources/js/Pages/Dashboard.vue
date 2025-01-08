<script setup>
import MainApp from './MainApp.vue'
import AnalyticsAward from '@/Components/dashboard/AnalyticsAward.vue'
</script>

<template>
    <MainApp>
        <VRow class="match-height">
            <VCol
            cols="12"
            md="4"
            >
            <AnalyticsAward />
            </VCol>
        </VRow>
    </MainApp>
</template>


<style lang="scss">
.v-card .trophy {
  position: absolute;
  inline-size: 5.188rem;
  inset-block-end: 1.25rem;
  inset-inline-end: 1.25rem;
}
</style>
