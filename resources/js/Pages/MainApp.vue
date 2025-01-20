<template>
    <div>
        <Head :title="title" />
        <v-layout class="rounded rounded-md">
            <v-responsive class="border rounded" max-height="100%">
                <v-app :theme="theme" class="app_scroll">
                    <v-navigation-drawer bordered v-model="toggleLeftDrawer" :rail="miniDrawer" :permanent="WindowSize ?? false" :temporary="WindowSize ?? true" expand-on-hover @mouseenter="mouseenter" @mouseleave="mouseleave" :class="['custom-scroll']"
                        :style="{'z-index': 99, height: '100vh', position: 'fixed', bottom: 0, 'background-color': switchColor ? '#64607F' : '', color: switchColor ? 'white' : ''}">
                        <v-card :color="colorStore.color" elevation="5" style="position: sticky;top:0;z-index: 100;">
                            <v-list>
                                <v-list-item link class="text-center pa-0">
                                    <v-list-item-title>
                                        <v-avatar color="grey lighten-5" size="50" v-show="!miniDrawer">
                                            <v-img :src="getImageUrl($page.props.auth.user.profile_photo_path)" />
                                        </v-avatar>
                                        <v-avatar color="grey lighten-5" size="40" v-show="miniDrawer">
                                            <v-img :src="getImageUrl($page.props.auth.user.profile_photo_path)" />
                                        </v-avatar>
                                    </v-list-item-title>
                                    <v-list-item-subtitle class="my-3">
                                        {{ $page.props.auth.user.email }}
                                    </v-list-item-subtitle>
                                    <v-list-item-title>
                                        {{ $page.props.auth.user.name }}
                                    </v-list-item-title>
                                </v-list-item>
                            </v-list>
                        </v-card>
                        <!-- --------------large------------------>
                        <v-treeview v-show="!miniDrawer" floating :items="items" class="mx-2" activatable open-on-click :opened="initiallyOpen" transition>
                            <template v-slot:prepend="{ item }">
                                <v-icon>{{ item.icon }}</v-icon>
                            </template>
                            <template v-slot:title="{ item }">
                                <v-list-item-title>{{ $t('menu.' + item.url) }}</v-list-item-title>
                            </template>
                            <template v-slot:item="{ item }">
                                <Link :href="item.url" :active="route().current(item.url)">
                                    <v-list-item :color="'warning'" rounded :value="item.url" class="mt-3" :class="{ 'bg-active': $page.url === '/'+item.url }">
                                        <template v-slot:prepend>
                                            <v-icon class="mr-2 ml-2" :icon="item.icon"></v-icon>
                                            <v-list-item-title style="white-space: nowrap;overflow: hidden; text-overflow: ellipsis;display: inline-block;max-width: 170px; ">{{ $t('menu.' + item.url) }}</v-list-item-title>
                                        </template>
                                    </v-list-item>
                                </Link>
                            </template>
                        </v-treeview>

                        <!-- --------------mini------------------->
                        <v-list v-show="miniDrawer" dense class="list-mini">
                            <v-list-item v-for="item in items" :key="item.id" link :href="item.url" :value="item.url" class="mt-3" :class="{ 'bg-active': $page.url === '/'+item.url }" rounded>
                                <v-icon size="small">{{ item.icon }}</v-icon>
                            </v-list-item>
                        </v-list>
                        <v-treeview v-show="miniDrawer" floating :items="items" class="mx-2 treeview-mini" activatable open-on-click :opened="initiallyOpen" transition>
                            <template v-slot:prepend="{ item }">
                                <v-icon>{{ item.icon }}</v-icon>
                            </template>
                            <template v-slot:title="{ item }">
                                <v-list-item-title>{{ $t('menu.' + item.url) }}</v-list-item-title>
                            </template>
                            <template v-slot:item="{ item }">
                                <Link :href="item.url">
                                    <v-list-item color="warning" rounded :value="item.url" class="mt-3" :class="{ 'bg-active': $page.url === '/'+item.url }">
                                        <template v-slot:prepend>
                                            <v-icon class="mr-2 ml-5" :icon="item.icon"></v-icon>
                                            <v-list-item-title>{{ $t('menu.' + item.url) }}</v-list-item-title>
                                        </template>
                                    </v-list-item>
                                </Link>
                            </template>
                        </v-treeview>

                        <!-- Logout -->
                        <template v-slot:append>
                            <div class="pa-2">
                                <v-btn block :color="colorStore.color" v-if="!miniDrawer" @click="logout">
                                    <v-icon>mdi-logout</v-icon>{{ $t('global.logout') }}
                                </v-btn>
                                <v-btn v-else style="min-width: 100%; max-width: 100%;" :color="colorStore.color" @click="logout">
                                    <div class="d-flex align-center">
                                        <v-icon>mdi-logout</v-icon>
                                        <v-list-item-title :class="['text-uppercase', 'custom-font', { 'hidden-text': drawerWidth <= 50 }]">{{ $t('global.logout') }}</v-list-item-title>
                                    </div>
                                </v-btn>
                            </div>
                        </template>
                    </v-navigation-drawer>
                    <v-navigation-drawer v-model="toggleRightDrawer" location="right" temporary :width="400" style="z-index:999;height:100vh;position:fixed;bottom:0;">
                        <v-toolbar style="position: sticky;top:0;z-index: 99;">
                            <v-list>
                                <v-list-item :title="$t('drawer.theme_customize')" :subtitle="$t('drawer.customize_preview_real_time')"></v-list-item>
                            </v-list>
                            <v-spacer></v-spacer>
                            <v-btn icon variant="text" @click.stop="toggleRightDrawer = !toggleRightDrawer">
                                <v-icon>mdi-close</v-icon>
                            </v-btn>
                        </v-toolbar>
                        <v-container>
                            <v-list-item-title class="mt-2">{{ $t('drawer.primary_color') }}</v-list-item-title>
                            <div class="pa-0 ma-0 mt-3 d-flex" style="gap: 10px;">
                                <v-btn
                                v-for="(color, index) in colors"
                                :key="index"
                                width="40"
                                class="d-flex align-center justify-center elevation-2 rounded-lg pa-6"
                                variant="outlined"
                                :color="selectedColor === color ? color : 'grey-darken-3'"
                                @click="clickColor(color)"
                                >
                                    <v-card :color="color" height="30" width="35" class="d-flex align-center justify-center"></v-card>
                                </v-btn>
                                <v-btn
                                width="40"
                                class="d-flex align-center justify-center elevation-2 rounded-lg pa-6"
                                variant="outlined"
                                :color="colorPicker"
                                @click="toggleColorPicker"
                                >
                                    <v-menu
                                    v-model="showColorPicker"
                                    :close-on-content-click="false"
                                    location="bottom"
                                    offset-y transition="scale-transition"
                                    style="background-color: transparent;"
                                    >
                                        <template v-slot:activator="{ props }">
                                            <v-card v-bind="props" elevation="0" style="box-shadow: none;background-color: transparent;" height="100%" width="100%" class="d-flex align-center justify-center"><v-icon size="25" :color="colorPicker">mdi-palette-outline</v-icon></v-card>
                                        </template>
                                        <v-color-picker
                                        v-model="colorPicker" elevation="15" :width="250"
                                        class="color-picker-dropdown"
                                        @update:modelValue="UpdateColor"
                                        ></v-color-picker>
                                    </v-menu>
                                </v-btn>
                            </div>
                            <v-list-item-title class="mt-5">{{ $t('drawer.theme') }}</v-list-item-title>
                            <div class="pa-0 ma-0 mt-3">
                                <v-row>
                                    <v-col cols="4">
                                        <v-btn :color="theme == 'light' ? colorStore.color : 'grey-darken-3'" variant="outlined" @click="ChangeTheme('light')" style="height: 50px; width: 100%;">
                                            <v-card color="transparent" style="box-shadow: none;" class="d-flex align-center justify-center">
                                                <v-icon size="30">mdi-weather-sunny</v-icon>
                                            </v-card>
                                        </v-btn>
                                        <p class="text-caption">{{ $t('global.light_theme') }}</p>
                                    </v-col>

                                    <v-col cols="4">
                                        <v-btn :color="theme == 'dark' ? colorStore.color : 'grey-darken-3'" variant="outlined" @click="ChangeTheme('dark')" style="height: 50px; width: 100%;">
                                            <v-card color="transparent" style="box-shadow: none;" class="d-flex align-center justify-center">
                                                <v-icon size="30">mdi-weather-night</v-icon>
                                            </v-card>
                                        </v-btn>
                                        <p class="text-caption">{{ $t('global.dark_theme') }}</p>
                                    </v-col>

                                    <v-col cols="4">
                                        <v-btn :color="theme == 'system' ? colorStore.color : 'grey-darken-3'" variant="outlined" @click="ChangeTheme('system')" style="height: 50px; width: 100%;">
                                            <v-card color="transparent" style="box-shadow: none;" class="d-flex align-center justify-center">
                                                <v-icon size="30">mdi-monitor</v-icon>
                                            </v-card>
                                        </v-btn>
                                        <p class="text-caption">{{ $t('drawer.system') }}</p>
                                    </v-col>
                                </v-row>
                            </div>
                            <v-list-item-title class="mt-5">{{ $t('drawer.skin') }}</v-list-item-title>
                            <div class="pa-0 ma-0 mt-3">
                                <v-row>
                                    <v-col cols="4">
                                        <v-btn :color="selectedSkin === 'default' ? colorStore.color : 'transparent'" height="70" class="pa-0" variant="outlined" @click="selectSkin('default')">
                                            <v-card color="transparent" style="box-shadow: none;" class="d-flex align-center justify-center">
                                                <img
                                                    src="../images/misc/skin-default.png"
                                                    :style="{'height': '65px','object-fit': 'contain','background-color': theme == 'dark' ? 'darkslategray' : 'transparent'}"
                                                    class="d-flex align-center justify-center"
                                                />
                                            </v-card>
                                        </v-btn>
                                        <p class="text-caption">{{ $t('drawer.default') }}</p>
                                    </v-col>

                                    <v-col cols="4">
                                        <v-btn :color="selectedSkin === 'bordered' ? colorStore.color : 'transparent'" height="70" class="pa-0" variant="outlined" @click="selectSkin('bordered')">
                                            <v-card color="transparent" style="box-shadow: none;" class="d-flex align-center justify-center">
                                                <img
                                                    src="../images/misc/skin-border.png"
                                                    :style="{'height': '65px','object-fit': 'contain','background-color': theme == 'dark' ? 'darkslategray' : 'transparent'}"
                                                    class="d-flex align-center justify-center"
                                                />
                                            </v-card>
                                        </v-btn>
                                        <p class="text-caption">{{ $t('drawer.bordered') }}</p>
                                    </v-col>
                                </v-row>
                            </div>
                            <div v-show="theme == 'light'" class="pa-0 ma-0 mt-3">
                                <v-toolbar class="pa-0 ma-0" color="transparent">
                                    <v-list class="pa-0 ma-0">
                                        <v-list-item class="pa-0 ma-0" title="Semi Dark Menu"></v-list-item>
                                    </v-list>
                                    <v-spacer></v-spacer>
                                    <v-switch v-model="switchColor" color="primary" inset hide-details ></v-switch>
                                </v-toolbar>
                            </div>
                        </v-container>
                        <v-divider :thickness="1" :class="['my-2', { 'border-opacity-50': theme === 'dark', 'border-opacity-100': theme !== 'dark' }]"></v-divider>
                        <v-container>
                            <v-list-item-title class="mt-2">{{ $t('drawer.layout') }}</v-list-item-title>
                            <div class="pa-0 ma-0 mt-3">
                                <v-row>
                                    <v-col cols="4">
                                        <v-btn :color="selectedLayout === 'Vertical' ? colorStore.color : 'transparent'" height="70" class="pa-0" variant="outlined" @click="selectLayout('Vertical')" :disabled="hideDrawer == '/user/profile' ? true : false">
                                            <v-card color="transparent" style="box-shadow: none;" class="d-flex align-center justify-center">
                                                <img
                                                    src="../images/misc/skin-default.png"
                                                    :style="{'height': '65px','object-fit': 'contain','background-color': theme == 'dark' ? 'darkslategray' : 'transparent'}"
                                                    class="d-flex align-center justify-center"
                                                />
                                            </v-card>
                                        </v-btn>
                                        <p class="text-caption">{{ $t('drawer.vertical') }}</p>
                                    </v-col>

                                    <v-col cols="4">
                                        <v-btn :color="selectedLayout === 'Collapsed' ? colorStore.color : 'transparent'" height="70" class="pa-0" variant="outlined" @click="selectLayout('Collapsed')" :disabled="hideDrawer == '/user/profile' ? true : false">
                                            <v-card color="transparent" style="box-shadow: none;" class="d-flex align-center justify-center">
                                                <img
                                                    src="../images/misc/Collapsed.png"
                                                    :style="{'height': '65px','object-fit': 'contain','background-color': theme == 'dark' ? 'darkslategray' : 'transparent'}"
                                                    class="d-flex align-center justify-center"
                                                />
                                            </v-card>
                                        </v-btn>
                                        <p class="text-caption">{{ $t('drawer.collapsed') }}</p>
                                    </v-col>

                                    <v-col cols="4">
                                        <v-btn :color="selectedLayout === 'Horizontal' ? colorStore.color : 'transparent'" height="70" class="pa-0" variant="outlined" @click="selectLayout('Horizontal')" :disabled="hideDrawer == '/user/profile' ? true : false">
                                            <v-card color="transparent" style="box-shadow: none;" class="d-flex align-center justify-center">
                                                <img
                                                    src="../images/misc/Horizontal.png"
                                                    :style="{'height': '65px','object-fit': 'contain','background-color': theme == 'dark' ? 'darkslategray' : 'transparent'}"
                                                    class="d-flex align-center justify-center"
                                                />
                                            </v-card>
                                        </v-btn>
                                        <p class="text-caption">{{ $t('drawer.horizontal') }}</p>
                                    </v-col>
                                </v-row>
                            </div>
                            <v-list-item-title class="mt-5">{{ $t('drawer.content') }}</v-list-item-title>
                            <div class="pa-0 ma-0 mt-3">
                                <v-row>
                                    <v-col cols="4">
                                        <v-btn :color="selectedContent === 'Compact' ? colorStore.color : 'transparent'" height="70" class="pa-0" variant="outlined" @click="selectContent('Compact')">
                                            <v-card color="transparent" style="box-shadow: none;" class="d-flex align-center justify-center">
                                                <img
                                                    src="../images/misc/Compact.png"
                                                    :style="{'height': '65px','object-fit': 'contain','background-color': theme == 'dark' ? 'darkslategray' : 'transparent'}"
                                                    class="d-flex align-center justify-center"
                                                />
                                            </v-card>
                                        </v-btn>
                                        <p class="text-caption">{{ $t('drawer.compact') }}</p>
                                    </v-col>

                                    <v-col cols="4">
                                        <v-btn :color="selectedContent === 'Wide' ? colorStore.color : 'transparent'" height="70" class="pa-0" variant="outlined" @click="selectContent('Wide')">
                                            <v-card color="transparent" style="box-shadow: none;" class="d-flex align-center justify-center">
                                                <img
                                                    src="../images/misc/Wide.png"
                                                    :style="{'height': '65px','object-fit': 'contain','background-color': theme == 'dark' ? 'darkslategray' : 'transparent'}"
                                                    class="d-flex align-center justify-center"
                                                />
                                            </v-card>
                                        </v-btn>
                                        <p class="text-caption">{{ $t('drawer.wide') }}</p>
                                    </v-col>
                                </v-row>
                            </div>
                            <v-list-item-title class="mt-5">{{ $t('drawer.direction') }}</v-list-item-title>
                            <div class="pa-0 ma-0 mt-3">
                                <v-row>
                                    <v-col cols="4">
                                        <v-btn :color="selectedDirection === 'Left' ? colorStore.color : 'transparent'" height="70" class="pa-0" variant="outlined" @click="selectDirection('Left')">
                                            <v-card color="transparent" style="box-shadow: none;" class="d-flex align-center justify-center">
                                                <img
                                                    src="../images/misc/Left-to-right.png"
                                                    :style="{'height': '65px','object-fit': 'contain','background-color': theme == 'dark' ? 'darkslategray' : 'transparent'}"
                                                    class="d-flex align-center justify-center"
                                                />
                                            </v-card>
                                        </v-btn>
                                        <p class="text-caption">{{ $t('drawer.left_to_right') }}</p>
                                    </v-col>

                                    <v-col cols="4">
                                        <v-btn :color="selectedDirection === 'Right' ? colorStore.color : 'transparent'" height="70" class="pa-0" variant="outlined" @click="selectDirection('Right')">
                                            <v-card color="transparent" style="box-shadow: none;" class="d-flex align-center justify-center">
                                                <img
                                                    src="../images/misc/Right-to-left.png"
                                                    :style="{'height': '65px','object-fit': 'contain','background-color': theme == 'dark' ? 'darkslategray' : 'transparent'}"
                                                    class="d-flex align-center justify-center"
                                                />
                                            </v-card>
                                        </v-btn>
                                        <p class="text-caption">{{ $t('drawer.right_to_left') }}</p>
                                    </v-col>
                                </v-row>
                            </div>
                        </v-container>
                    </v-navigation-drawer>
                    <v-app-bar style="position: fixed;top:0;z-index: 98;">
                        <v-app-bar-nav-icon v-show="hideDrawer !== '/user/profile' ? true : false" v-if="colorStore.selectedLayout !== 'Horizontal' || selectedLayout !== 'Horizontal'" @click="toggleDrawerLeft"></v-app-bar-nav-icon>
                        <v-app-bar-title>{{ $page.url.split('/').pop().toLocaleUpperCase() }}</v-app-bar-title>
                        <template v-slot:append>
                            <div class="d-flex justify-end align-center">
                                <v-menu offset-y>
                                    <template #activator="{ props }">
                                        <v-btn v-bind="props" icon>
                                            <v-icon>
                                                mdi-translate
                                            </v-icon>
                                        </v-btn>
                                    </template>
                                    <v-list>
                                        <v-list-item :prepend-avatar="getFlagImage('logos/english_flag.jpg')" @click="ChangeLanguage('en')">
                                            <v-list-item-title>{{ $t('global.english') }}</v-list-item-title>
                                        </v-list-item>
                                        <v-list-item class="mt-3" :prepend-avatar="getFlagImage('logos/cambodia_flag.png')" @click="ChangeLanguage('kh')">
                                            <v-list-item-title>{{ $t('global.khmer') }}</v-list-item-title>
                                        </v-list-item>
                                    </v-list>
                                </v-menu>
                                <v-menu offset-y>
                                    <template #activator="{ props }">
                                        <v-btn v-bind="props" icon>
                                            <v-icon>
                                            {{ theme === 'light' ? 'mdi-weather-sunny' : 'mdi-weather-night' }}
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
                            <VBadge dot location="bottom right" offset-x="3" offset-y="3" class="mx-3" color="success" bordered>
                                <VAvatar class="cursor-pointer" color="primary" variant="tonal">
                                    <VImg :src="getImageUrl($page.props.auth.user.profile_photo_path)" />
                                    <VMenu activator="parent" width="230" location="bottom end" offset="14px">
                                        <VList>
                                            <VListItem>
                                                <template #prepend>
                                                    <VListItemAction start>
                                                        <VBadge dot location="bottom right" offset-x="3" offset-y="3"
                                                            color="success">
                                                            <VAvatar color="primary" variant="tonal">
                                                                <VImg :src="getImageUrl($page.props.auth.user.profile_photo_path)" />
                                                            </VAvatar>
                                                        </VBadge>
                                                    </VListItemAction>
                                                </template>
                                                <VListItemTitle class="font-weight-semibold">
                                                    {{ $page.props.auth.user.name }}
                                                </VListItemTitle>
                                                <VListItemSubtitle>{{ $page.props.auth.user.email }}</VListItemSubtitle>
                                            </VListItem>
                                            <VDivider class="my-2" />
                                            <VListItem link :href="route('profile.show')">
                                                <template #prepend>
                                                    <VIcon class="me-2" icon="mdi-account-tie" size="22" />
                                                </template>
                                                <VListItemTitle>{{ $t('global.profile') }}</VListItemTitle>
                                            </VListItem>
                                            <VListItem link @click="goHomePage">
                                                <template #prepend>
                                                    <VIcon class="me-2" icon="mdi-home-account" size="22" />
                                                </template>
                                                <VListItemTitle>{{ $t('global.home') }}</VListItemTitle>
                                            </VListItem>
                                            <VDivider class="my-2" />
                                            <VListItem @click="logout">
                                                <template #prepend>
                                                    <VIcon class="me-2" icon="mdi-logout" size="22" />
                                                </template>
                                                <VListItemTitle>{{ $t('global.logout') }}</VListItemTitle>
                                            </VListItem>
                                        </VList>
                                    </VMenu>
                                </VAvatar>
                            </VBadge>
                        </template>
                        <template v-slot:extension>
                            <v-container class="d-flex justify-center" fluid color="transparent">
                                <v-slide-group show-arrows center-active>
                                    <v-slide-group-item
                                        v-for="item in items"
                                        :key="item.id"
                                    >
                                        <v-list>
                                            <v-list-item
                                                link
                                                :class="{ 'bg-active': $page.url === '/'+item.url }"
                                                :href="item.url"
                                                :title="item.name"
                                                :prepend-icon="item.icon"
                                                rounded
                                                class="d-flex align-center mr-3"
                                            >
                                            </v-list-item>
                                        </v-list>
                                    </v-slide-group-item>
                                </v-slide-group>
                            </v-container>
                        </template>
                    </v-app-bar>
                    <v-main class="main" style="display: flex; flex-direction: column;min-width: 900px;">
                        <v-row class="pt-0 mt-0">
                            <v-col cols="12" class="content">
                                <v-container :fluid="colorStore.selectedContent !== 'Compact'" style="padding-bottom: 5px;">
                                    <slot />
                                </v-container>
                            </v-col>
                        </v-row>
                    </v-main>
                </v-app>
            </v-responsive>
        </v-layout>
        <v-btn v-if="showButton" style="position: fixed;bottom:70px;right:20px;z-index: 999;" icon="mdi-cog-outline" size="large"
            :color="colorStore.color" @click.stop="toggleRightDrawer = !toggleRightDrawer" class="rotate-animation setting_btn"></v-btn>
    </div>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { VTreeview } from 'vuetify/labs/VTreeview';
import { Inertia } from '@inertiajs/inertia';
import $ from 'jquery';
import { Store } from '@/store/index';
import axios from 'axios';
import { helper } from '@/helper';
import { useI18n } from 'vue-i18n';

const props = defineProps({
    title: String,
    sessions: Array,
});
const { locale } = useI18n();
const page = usePage();
const colorStore = Store();
const theme = ref(colorStore.theme);
const selectedSkin = ref(colorStore.selectedSkin);
const selectedLayout = ref(colorStore.selectedLayout);
const selectedContent = ref(colorStore.selectedContent);
const selectedDirection = ref(colorStore.selectedDirection);
const toggleLeftDrawer = ref(false);
const initiallyOpen = ref(['public']);
const toggleRightDrawer = ref(false);
const drawerWidth = ref(100);
const switchColor = ref(false);
const miniDrawer = ref(false);
const showColorPicker = ref(false);
const showButton = ref(false);
const colorPicker = ref(colorStore.color);
const selectedColor = ref(localStorage.getItem('selectedColor') || 'grey-darken-3');
const WindowSize = ref(false);
const items = ref([]);
const colors = ref(['#0D47A1', '#B71C1C', '#1B5E20', '#4A148C']);
const hideDrawer = ref(page.url);

watch(
  () => colorStore.theme,
  (newTheme) => {
    if (newTheme === 'dark') {
      switchColor.value = false;
    }
    theme.value = newTheme;
  }
);

watch(
  () => colorStore.selectedSkin,
  (newSkin) => {
    selectedSkin.value = newSkin;
  }
);

watch(
  () => colorStore.selectedLayout,
  (newLayout) => {
    selectedLayout.value = newLayout;
    if(colorStore.selectedLayout !== 'Horizontal'){
        $(".v-toolbar__extension").css({"display":"none"});
        $(".main").css({"padding-top":"50px"});
    }else{
        $(".v-toolbar__extension").css({"display":"flex"});
        $(".main").css({"padding-top":""});
    }
    helper.GetGridHeight()
  }
);

watch(
  () => colorStore.selectedContent,
  (newContent) => {
    selectedContent.value = newContent;
  }
);

watch(
  () => colorStore.selectedDirection,
  (newDirection) => {
    selectedDirection.value = newDirection;
  }
);

watch(
  () => miniDrawer,
  (newMiniDrawer) => {
    drawerWidth.value = newMiniDrawer ? 50 : 100;
  }
);

watch(
  () => colorStore.menuItem,
  (newItem) => {
    items.value = newItem.sort((a, b) => a.order - b.order);
  }
);

watch(hideDrawer, async () => {
    if(hideDrawer.value == "/user/profile"){
        setTimeout(() => {
            toggleLeftDrawer.value = false;
            miniDrawer.value = false;
            selectedLayout.value = 'Horizontal';
        }, 200);
    }
  },
  { immediate: true }
);

const checkWindowSize = () => {
    if(hideDrawer.value == "/user/profile"){
        toggleLeftDrawer.value = false;
        miniDrawer.value = false;
    }
    const headElement = document.getElementsByClassName("head")[0];
    if (headElement) {
        const height = headElement.offsetHeight;
        const bodyHeight = document.getElementsByClassName("body")[0];
        bodyHeight.style.height = "calc(100% - " + height + "px)";
    }
    if(window.innerWidth > window.outerWidth * 0.5 && colorStore.selectedLayout == 'Collapsed'){
        miniDrawer.value = true;
    }
    if(colorStore.selectedLayout !== 'Horizontal') {
        WindowSize.value = window.innerWidth > window.outerWidth * 0.5 && window.outerWidth > 700;
    }else{
        toggleLeftDrawer.value = false;
        miniDrawer.value = false;
        WindowSize.value = false;
    }

    let left = $(".v-navigation-drawer--left").position().left;
    if (left < -0) {
        $(".app_scroll").attr("style", "overflow-x: scroll;");
        $(".app_scroll").attr("style", "overflow-y: hidden;");
    } else {
        setTimeout(() => {
            $(".app_scroll").removeAttr("style");
        }, 200);
    }
    showButton.value = window.innerWidth > window.outerWidth * 0.5 && window.outerWidth > 700;
};

onMounted(() => {
    helper.GetGridHeight();
    let left = $(".v-navigation-drawer--left").position().left;
    setTimeout(() => {
        if (left < -0 && toggleLeftDrawer.value == false && miniDrawer.value == false) {
            $(".app_scroll").attr("style", "overflow-x: scroll;");
            $(".app_scroll").attr("style", "overflow-y: hidden;");
        } else {
            $(".app_scroll").removeAttr("style");
        }
    }, 100);
    const txtsearch = document.getElementsByClassName("search")[0];
    let detailsElement = '';
    if(txtsearch != undefined){
        detailsElement = txtsearch.querySelector(".v-input__details");
    }

    if (detailsElement) {
        detailsElement.classList.remove("v-input__details");
    }
    if(page.url !== "/user/profile") {
        fetchDashboardData();
    }
    setTimeout(function(){
        $(".v-navigation-drawer").find(".treeview-mini").css({"display": "none"});
    }, 100);
    if(colorStore.selectedLayout !== 'Horizontal'){
        $(".v-toolbar__extension").css({"display":"none"});
        $(".main").css({"padding-top":"50px"});
    }else {
        $(".main").css({"padding-top":""});
    }
    $(".v-toolbar__extension").css({"border":"1px solid dimgray"});
    $(".custom-scroll").find(".v-navigation-drawer__content").css({"overflow": "hidden"});
    checkWindowSize();
    window.addEventListener('resize', checkWindowSize);
    var selectColor = localStorage.getItem('checkSelectedColor');
    miniDrawer.value = colorStore.selectedLayout == 'Vertical' ? false : true;
    if(selectColor == null || selectColor == undefined){
        colorPicker.value = colorStore.color;
    }else {
        if(selectColor == 'false'){
            colorPicker.value = colorStore.color;
        }else{
            colorPicker.value = '#424242';
        }
    }
    if(colorStore.selectedLayout == 'Collapsed'){
        drawerWidth.value = 50;
    }else if(colorStore.selectedLayout == 'Horizontal'){
        toggleLeftDrawer.value = false;
        miniDrawer.value = false;
        WindowSize.value = false;
    }
});

const logout = () => {
    Inertia.post(route('logout'));
};

function ChangeLanguage(lang) {
    locale.value = lang;
    localStorage.setItem('locale', lang);
};

function ChangeTheme(newTheme) {
    colorStore.setTheme(newTheme);
};

function toggleDrawerLeft(){
    if(WindowSize.value){
        toggleLeftDrawer.value = true;
        miniDrawer.value = !miniDrawer.value
        if(miniDrawer.value){
            setTimeout(function(){
                $(".v-navigation-drawer").find(".treeview-mini").css({"display": "none"});
            }, 100);
            drawerWidth.value = 50;
            colorStore.setSelectedLayout('Collapsed');
        }else{
            colorStore.setSelectedLayout('Vertical');
        }
    }else{
        miniDrawer.value = false;
        toggleLeftDrawer.value = true;
        if(miniDrawer.value){
            setTimeout(function(){
                $(".v-navigation-drawer").find(".treeview-mini").css({"display": "none"});
            }, 100);
            colorStore.setSelectedLayout('Collapsed');
        }else{
            colorStore.setSelectedLayout('Vertical');
        }
    }
};

function mouseenter() {
    drawerWidth.value = 100;
    $(".custom-scroll").find(".v-navigation-drawer__content").css({"overflow": "auto"});
    if(miniDrawer.value == true){
        $(".list-mini").css({"display": "none"});
        $(".treeview-mini").css({"display": ""});
    }
};

function mouseleave() {
    drawerWidth.value = 50;
    $(".custom-scroll").find(".v-navigation-drawer__content").css({"overflow": "hidden"});
    if(miniDrawer.value == true){
        $(".treeview-mini").css({"display": "none"});
        $(".list-mini").css({"display": ""});
    }
};

function toggleColorPicker() {
  showColorPicker.value = !showColorPicker.value;
};

function UpdateColor() {
    colorStore.setColor(colorPicker.value);
    localStorage.setItem('selectedColor', "grey-darken-3");
    localStorage.setItem('checkSelectedColor', false);
    selectedColor.value = "grey-darken-3";
};

function clickColor(newColor) {
    if (selectedColor.value === newColor) {
        selectedColor.value = 'grey-darken-3';
        colorStore.setColor('#424242');
    } else {
        selectedColor.value = newColor;
        colorStore.setColor(newColor);
    }
    colorPicker.value = '#424242';
    localStorage.setItem('selectedColor', selectedColor.value);
    localStorage.setItem('checkSelectedColor', true);
};

function selectSkin(skin) {
    colorStore.setSelectedSkin(skin);
};

function selectLayout(layout) {
    if(layout == 'Collapsed') {
        setTimeout(function(){
            $(".v-navigation-drawer").find(".treeview-mini").css({"display": "none"});
        }, 100);
        toggleLeftDrawer.value = true;
        drawerWidth.value = 50;
        miniDrawer.value = true;
    } else if(layout == 'Vertical'){
        toggleLeftDrawer.value = true;
        miniDrawer.value = false;
    } else {
        toggleLeftDrawer.value = false;
        miniDrawer.value = false;
    }
    colorStore.setSelectedLayout(layout);
};

function selectContent(content) {
    colorStore.setSelectedContent(content);
};

function selectDirection(direction) {
    colorStore.setSelectedDirection(direction);
};

const fetchDashboardData = async () => {
    try {
        const response = await axios.get('menu_system/getMenu');
        items.value = response.data.sort((a, b) => a.order - b.order);
    } catch (error) {
        console.error('Error fetching data', error);
    }
};

function goHomePage(){
    Inertia.get(route('dashboard'));
};

function getImageUrl(name) {
    return new URL(`/storage/app/public/${name}`, import.meta.url).href
};

function getFlagImage(name){
    return new URL(`/resources/js/images/${name}`, import.meta.url).href
}
</script>
<style>
.v-toolbar__extension {
    height: 60px !important;
    overflow: hidden;
}
.bg-active {
  background-color: rgb(var(--v-theme-warning)) !important;
  color : white !important;
}
.hidden-text {
  display: none;
}
.custom-font {
  font-size: 14px;
  letter-spacing: 1px;
  font-weight: 300;
}
</style>
