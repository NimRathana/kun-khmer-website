<template>
    <!-- <div class="min-h-screen flex flex-col sm:justify-center items-center pa-5 bg-red">
        <v-card>
            <div class="d-flex justify-center pt-5">
                <slot name="logo" />
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 shadow-md overflow-hidden sm:rounded-lg">
                <slot />
            </div>
        </v-card>
    </div> -->
    <v-container class="min-h-screen flex items-center justify-center">
        <v-card elevation="10" :variant="colorStore.selectedSkin == 'bordered' ? 'outlined' : 'elevated'" style="width: 100%; max-width: 500px;">
            <v-row justify="center" class="pt-5">
                <div class="d-flex justify-end" style="position: absolute;right:5px;top:10px;">
                    <v-menu offset-y>
                        <template #activator="{ props }">
                            <v-btn v-bind="props" icon variant="text">
                                <v-icon>
                                {{ colorStore.theme === 'light' ? 'mdi-weather-sunny' : 'mdi-weather-night' }}
                                </v-icon>
                            </v-btn>
                        </template>
                        <v-list>
                            <v-list-item @click="ChangeTheme('light')">
                                <v-list-item-title><v-icon>mdi-weather-sunny</v-icon> {{ $t('global.light_theme') }}</v-list-item-title>
                            </v-list-item>
                            <v-list-item @click="ChangeTheme('dark')">
                                <v-list-item-title><v-icon>mdi-weather-night</v-icon> {{ $t('global.dark_theme') }}</v-list-item-title>
                            </v-list-item>
                        </v-list>
                    </v-menu>
                </div>
                <v-col cols="auto">
                    <slot name="logo" />
                </v-col>
            </v-row>

            <v-card-text class="px-6 py-4">
                <slot />
            </v-card-text>
        </v-card>
    </v-container>
</template>
<script setup>
import { Store } from '@/store/index';

const colorStore = Store();
function ChangeTheme(newTheme) {
    colorStore.setTheme(newTheme);
};
</script>
