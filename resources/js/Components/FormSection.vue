<script setup>
import { computed, useSlots } from 'vue';
import { Store } from '@/store/index';

const colorStore = Store();

defineEmits(['submitted']);

const hasActions = computed(() => !! useSlots().actions);
</script>

<template>
    <VContainer fluid>
        <VRow>
            <VCol cols="12" md="3">
                <VCard style="height: 100%;align-content: center;background-color: transparent;box-shadow: none;">
                    <VCardTitle>
                        <slot name="title" />
                    </VCardTitle>
                    <VCardText>
                        <slot name="description" />
                    </VCardText>
                </VCard>
            </VCol>

            <VCol cols="12" md="9">
                <VForm @submit.prevent="$emit('submitted')">
                    <VCard :variant="colorStore.selectedSkin == 'bordered' ? 'outlined' : 'elevated'">
                        <div class="px-4 py-5">
                            <div class="grid grid-cols-6 gap-6">
                                <slot name="form" />
                            </div>
                        </div>

                        <VSpacer />

                        <div v-if="hasActions" class="px-4 py-3 text-end">
                            <slot name="actions" />
                        </div>
                    </VCard>
                </VForm>
            </VCol>
        </VRow>
    </VContainer>
</template>
