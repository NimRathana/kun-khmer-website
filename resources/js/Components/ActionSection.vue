<script setup>
import SectionTitle from './SectionTitle.vue';
import { Store } from '@/store/index';

const colorStore = Store();
</script>

<template>
    <VContainer fluid>
        <VRow class="mb-6">
            <VCol cols="12" md="3" style="align-content: center;">
                <SectionTitle>
                    <template #title>
                        <slot name="title" />
                    </template>
                    <template #description>
                        <slot name="description" />
                    </template>
                </SectionTitle>
            </VCol>

            <VCol cols="12" md="9">
                <VCard class="pa-5 shadow"  :variant="colorStore.selectedSkin == 'bordered' ? 'outlined' : 'elevated'">
                    <slot name="content" />
                </VCard>
            </VCol>
        </VRow>
    </VContainer>
</template>
