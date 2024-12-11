<script setup>
import { ref, watch } from 'vue';
import { Store } from '@/store/index';
import img from '@/images/misc/trophy.png';

const colorStore = Store();
const color = ref(colorStore.color);
watch(() => colorStore.color, (newColor) => {
  color.value = newColor;
});
</script>

<template>
  <VCard class="position-relative" :variant="colorStore.selectedSkin == 'bordered' ? 'outlined' : 'elevated'" elevation="5">
    <VCardText>
      <div class="mb-2">
        <h5 class="text-h5">
          Congratulations John! <span class="text-high-emphasis">🎉</span>
        </h5>
        <div class="text-body-1">
          Best seller of the month
        </div>
      </div>
      <h4 class="text-h4 text-primary">
        $42.8k
      </h4>
      <div class="text-body-1 mb-2">
        78% of target <span class="text-high-emphasis">🚀</span>
      </div>
      <VBtn size="small" :color="color">
        View Sales
      </VBtn>
    </VCardText>

    <!-- Trophy -->
    <VImg
      :src="img"
      class="trophy"
    />
  </VCard>
</template>

<style lang="scss">
.v-card .trophy {
  position: absolute;
  inline-size: 5.188rem;
  inset-block-end: 1.25rem;
  inset-inline-end: 1.25rem;
}
</style>
