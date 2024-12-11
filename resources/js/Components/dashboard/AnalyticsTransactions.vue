<script setup>
import { ref, watch } from 'vue';
import { Store } from '@/store/index';

const colorStore = Store();
const color = ref(colorStore.color);
watch(() => colorStore.color, (newColor) => {
  color.value = newColor;
});
const statistics = [
  {
    title: 'Sales',
    stats: '245k',
    icon: 'mdi-sprout',
    color: 'primary',
  },
  {
    title: 'Customers',
    stats: '12.5k',
    icon: 'mdi-sprout',
    color: 'success',
  },
  {
    title: 'Product',
    stats: '1.54k',
    icon: 'mdi-sprout',
    color: 'warning',
  },
  {
    title: 'Revenue',
    stats: '$88k',
    icon: 'mdi-sprout',
    color: 'info',
  },
]

const moreList = [
  {
    title: 'Share',
    value: 'Share',
  },
  {
    title: 'Refresh',
    value: 'Refresh',
  },
  {
    title: 'Update',
    value: 'Update',
  },
]
</script>

<template>
  <VCard title="Transactions" :variant="colorStore.selectedSkin == 'bordered' ? 'outlined' : 'elevated'" elevation="5">
    <template #subtitle>
      <p class="text-body-1 mb-0">
        <span class="d-inline-block font-weight-medium text-high-emphasis">Total 48.5% Growth</span> <span class="text-high-emphasis">😎</span> this month
      </p>
    </template>

    <VCardText class="pt-10">
      <VRow>
        <VCol
          v-for="item in statistics"
          :key="item.title"
          cols="12"
          sm="6"
          md="3"
        >
          <div class="d-flex align-center gap-x-3">
            <VAvatar
              :color="item.color"
              rounded
              size="40"
              class="elevation-2"
            >
              <VIcon
                size="24"
                :icon="item.icon"
              />
            </VAvatar>

            <div class="d-flex flex-column">
              <div class="text-body-1">
                {{ item.title }}
              </div>
              <h5 class="text-h5">
                {{ item.stats }}
              </h5>
            </div>
          </div>
        </VCol>
      </VRow>
    </VCardText>
  </VCard>
</template>
