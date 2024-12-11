<script setup>
import MainApp from './MainApp.vue'
import AnalyticsAward from '@/Components/dashboard/AnalyticsAward.vue'
import AnalyticsDepositWithdraw from '@/Components/dashboard/AnalyticsDepositWithdraw.vue'
import AnalyticsSalesByCountries from '@/Components/dashboard/AnalyticsSalesByCountries.vue'
import AnalyticsTotalEarning from '@/Components/dashboard/AnalyticsTotalEarning.vue'
import AnalyticsTransactions from '@/Components/dashboard/AnalyticsTransactions.vue'
import AnalyticsUserTable from '@/Components/dashboard/AnalyticsUserTable.vue'
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

            <VCol
            cols="12"
            md="8"
            >
            <AnalyticsTransactions />
            </VCol>

            <VCol
            cols="12"
            md="8"
            >
            <AnalyticsDepositWithdraw />
            </VCol>

            <VCol
            cols="12"
            md="4"
            >
            <AnalyticsTotalEarning />
            </VCol>

            <VCol
            cols="12"
            md="4"
            >
            <AnalyticsSalesByCountries />
            </VCol>

            <VCol cols="12" md="8">
            <AnalyticsUserTable />
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
