<template>
    <div>
        <v-layout class="rounded rounded-md">
            <v-responsive class="border rounded">
                <v-app :theme="theme" style="display: flex;align-items: center;justify-content: center;overflow-y: hidden;overflow-x: auto;">
                    <v-container :fluid="colorStore.selectedContent !== 'Compact'" class="pa-0 shadow mb-5 position-relative" style="border: 1px solid pink;min-width: 1000px;">
                        <v-system-bar window style="position: sticky;top: 0;">
                            <Link :href="route('register')" class="text-sm text-blue-600 hover:text-blue-400 border-hidden">
                                <span class="ms-2 text-sm">register</span>
                            </Link>
                            <Link :href="route('login')" class="text-sm text-blue-600 hover:text-blue-400 border-hidden">
                                <span class="ms-2 text-sm">login</span>
                            </Link>

                            <v-spacer></v-spacer>

                            <v-icon icon="mdi-wifi-strength-4"></v-icon>
                            <v-icon icon="mdi-signal" class="ms-2"></v-icon>
                            <v-icon icon="mdi-battery" class="ms-2"></v-icon>

                            <span class="ms-2">3:13PM</span>
                        </v-system-bar>
                        <v-card
                            class="mx-auto d-flex justify-center"
                            href="https://github.com/vuetifyjs/vuetify/"
                            max-width="100%"
                            height="150"
                            target="_blank"
                            color="light-blue-darken-4"
                        >
                            <v-card-item>
                                <v-img  v-if="company_profile && company_profile.logo" :src="getStorageImageUrl('CompanyProfile/'+company_profile.logo)" style="width: 140px;height: 140px;border-radius: 5px;" cover alt="Company Logo"></v-img>
                            </v-card-item>
                            <v-card-item class="text-center">
                                <v-card-title style="font-size: 3rem;">
                                    {{ company_profile.name_km }}
                                </v-card-title>

                                <v-card-title style="font-size: 2rem;">
                                    {{ company_profile.name_en }}
                                </v-card-title>
                            </v-card-item>
                        </v-card>

                        <v-row class="mx-auto mt-1" justify="start" align="center" dense>
                            <v-col cols="12" sm="6" md="4" lg="3" v-for="(item, index) in items" :key="index">
                                <v-card class="mx-auto mt-1" max-width="400" height="300" variant="text">
                                    <v-img
                                        :src="getImageUrl(item.image)"
                                        width="400"
                                        height="200"
                                        style="border-radius: 5px;"
                                    ></v-img>
                                    <v-card-text class="text-center pa-2">
                                        <div class="font-weight-bold text-clamp">{{ item.label }}</div>
                                    </v-card-text>
                                </v-card>
                            </v-col>
                        </v-row>

                        <v-main class="pa-5">
                            <v-row>
                                <v-col style="max-width: 300px;">
                                1
                                </v-col>
                                <v-col>
                                <h1>2</h1>
                                </v-col>
                                <v-col style="max-width: 300px;">
                                3
                                </v-col>
                            </v-row>

                            <v-row class="my-5">
                                <v-col cols="12" class="pa-0">
                                    <h6 class="Muol-Light p-3 bg-success text-white">វីឌីអូសំខាន់ៗ</h6>
                                </v-col>
                                <v-col cols="12" class="grid-container m-3">
                                    <a href="https://www.youtube.com/embed/aLkTDdx8aS8?si=02EusmijT5ROthGA" class="grid-item">
                                        <img src="https://beltei.edu.kh/asset/img/biu/speaking/youtube-logo.png" class="position-absolute" width="20%" alt="">
                                        <img class="w-100 youtube-thumbnail" src="https://img.youtube.com/vi/aLkTDdx8aS8/maxresdefault.jpg" alt="YouTube Video Thumbnail" data-src="https://www.youtube.com/embed/aLkTDdx8aS8?si=02EusmijT5ROthGA">
                                    </a>
                                    <a href="https://www.youtube.com/embed/1cHgtZUcEl8?si=-qnYdJks4l357rEO" class="grid-item">
                                        <img src="https://beltei.edu.kh/asset/img/biu/speaking/youtube-logo.png" class="position-absolute" width="20%" alt="">
                                        <img class="w-100 youtube-thumbnail" src="https://img.youtube.com/vi/1cHgtZUcEl8/maxresdefault.jpg" alt="YouTube Video Thumbnail" data-src="https://www.youtube.com/embed/1cHgtZUcEl8?si=-qnYdJks4l357rEO">
                                    </a>
                                    <a href="https://www.youtube.com/embed/7Bozi7A_9lw?si=2R0s3VpOkFck2jPr" class="grid-item">
                                        <img src="https://beltei.edu.kh/asset/img/biu/speaking/youtube-logo.png" class="position-absolute" width="20%" alt="">
                                        <img class="w-100 youtube-thumbnail" src="https://img.youtube.com/vi/7Bozi7A_9lw/maxresdefault.jpg" alt="YouTube Video Thumbnail" data-src="https://www.youtube.com/embed/7Bozi7A_9lw?si=2R0s3VpOkFck2jPr">
                                    </a>
                                    <a href="https://www.youtube.com/embed/0ha5Xtf6BLo?si=zYAql8Nj-nVJ-4-H" class="grid-item">
                                        <img src="https://beltei.edu.kh/asset/img/biu/speaking/youtube-logo.png" class="position-absolute" width="20%" alt="">
                                        <img class="w-100 youtube-thumbnail" src="https://img.youtube.com/vi/0ha5Xtf6BLo/maxresdefault.jpg" alt="YouTube Video Thumbnail" data-src="https://www.youtube.com/embed/0ha5Xtf6BLo?si=zYAql8Nj-nVJ-4-H">
                                    </a>
                                </v-col>
                                <v-col cols="12" class="grid-container m-3">
                                    <a href="https://www.youtube.com/embed/aLkTDdx8aS8?si=02EusmijT5ROthGA" class="grid-item">
                                        <img src="https://beltei.edu.kh/asset/img/biu/speaking/youtube-logo.png" class="position-absolute" width="20%" alt="">
                                        <img class="w-100 youtube-thumbnail" src="https://img.youtube.com/vi/aLkTDdx8aS8/maxresdefault.jpg" alt="YouTube Video Thumbnail" data-src="https://www.youtube.com/embed/aLkTDdx8aS8?si=02EusmijT5ROthGA">
                                    </a>
                                    <a href="https://www.youtube.com/embed/1cHgtZUcEl8?si=-qnYdJks4l357rEO" class="grid-item">
                                        <img src="https://beltei.edu.kh/asset/img/biu/speaking/youtube-logo.png" class="position-absolute" width="20%" alt="">
                                        <img class="w-100 youtube-thumbnail" src="https://img.youtube.com/vi/1cHgtZUcEl8/maxresdefault.jpg" alt="YouTube Video Thumbnail" data-src="https://www.youtube.com/embed/1cHgtZUcEl8?si=-qnYdJks4l357rEO">
                                    </a>
                                    <a href="https://www.youtube.com/embed/7Bozi7A_9lw?si=2R0s3VpOkFck2jPr" class="grid-item">
                                        <img src="https://beltei.edu.kh/asset/img/biu/speaking/youtube-logo.png" class="position-absolute" width="20%" alt="">
                                        <img class="w-100 youtube-thumbnail" src="https://img.youtube.com/vi/7Bozi7A_9lw/maxresdefault.jpg" alt="YouTube Video Thumbnail" data-src="https://www.youtube.com/embed/7Bozi7A_9lw?si=2R0s3VpOkFck2jPr">
                                    </a>
                                    <a href="https://www.youtube.com/embed/0ha5Xtf6BLo?si=zYAql8Nj-nVJ-4-H" class="grid-item">
                                        <img src="https://beltei.edu.kh/asset/img/biu/speaking/youtube-logo.png" class="position-absolute" width="20%" alt="">
                                        <img class="w-100 youtube-thumbnail" src="https://img.youtube.com/vi/0ha5Xtf6BLo/maxresdefault.jpg" alt="YouTube Video Thumbnail" data-src="https://www.youtube.com/embed/0ha5Xtf6BLo?si=zYAql8Nj-nVJ-4-H">
                                    </a>
                                </v-col>
                            </v-row>
                        </v-main>
                    </v-container>

                    <v-navigation-drawer v-model="toggleRightDrawer" location="right" temporary :width="400" style="z-index:999;height:100vh;position:fixed;bottom:0;top:0;">
                        <v-toolbar style="position: sticky;top:0;z-index: 99;">
                            <v-list>
                                <v-list-item title="Theme Customize" subtitle="Customize & Preview in Real Time"></v-list-item>
                            </v-list>
                            <v-spacer></v-spacer>
                            <v-btn icon variant="text" @click.stop="toggleRightDrawer = !toggleRightDrawer">
                                <v-icon>mdi-close</v-icon>
                            </v-btn>
                        </v-toolbar>
                        <v-container>
                            <v-chip
                            :color="colorStore.color" size="small"
                            label
                            >
                                Theming
                            </v-chip>
                            <v-list-item-title class="mt-5">Primary Color</v-list-item-title>
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
                            <v-list-item-title class="mt-5">Theme</v-list-item-title>
                            <div class="pa-0 ma-0 mt-3">
                                <v-row>
                                    <v-col cols="4">
                                        <v-btn :color="theme == 'light' ? colorStore.color : 'grey-darken-3'" variant="outlined" @click="ChangeTheme('light')" style="height: 50px; width: 100%;">
                                            <v-card color="transparent" style="box-shadow: none;" class="d-flex align-center justify-center">
                                                <v-icon size="30">mdi-weather-sunny</v-icon>
                                            </v-card>
                                        </v-btn>
                                        <p class="text-caption">Light</p>
                                    </v-col>

                                    <v-col cols="4">
                                        <v-btn :color="theme == 'dark' ? colorStore.color : 'grey-darken-3'" variant="outlined" @click="ChangeTheme('dark')" style="height: 50px; width: 100%;">
                                            <v-card color="transparent" style="box-shadow: none;" class="d-flex align-center justify-center">
                                                <v-icon size="30">mdi-weather-night</v-icon>
                                            </v-card>
                                        </v-btn>
                                        <p class="text-caption">Dark</p>
                                    </v-col>

                                    <v-col cols="4">
                                        <v-btn :color="theme == 'system' ? colorStore.color : 'grey-darken-3'" variant="outlined" @click="ChangeTheme('system')" style="height: 50px; width: 100%;">
                                            <v-card color="transparent" style="box-shadow: none;" class="d-flex align-center justify-center">
                                                <v-icon size="30">mdi-monitor</v-icon>
                                            </v-card>
                                        </v-btn>
                                        <p class="text-caption">System</p>
                                    </v-col>
                                </v-row>
                            </div>
                            <v-list-item-title class="mt-5">Skins</v-list-item-title>
                            <div class="pa-0 ma-0 mt-3">
                                <v-row>
                                    <v-col cols="4">
                                        <v-btn :color="selectedSkin === 'default' ? colorStore.color : 'transparent'" height="70" class="pa-0" variant="outlined" @click="selectSkin('default')">
                                            <v-card color="transparent" style="box-shadow: none;" class="d-flex align-center justify-center">
                                                <img
                                                    :src="getImageUrl('misc/skin-default.png')"
                                                    :style="{'height': '65px','object-fit': 'contain','background-color': theme == 'dark' ? 'darkslategray' : 'transparent'}"
                                                    class="d-flex align-center justify-center"
                                                />
                                            </v-card>
                                        </v-btn>
                                        <p class="text-caption">Default</p>
                                    </v-col>

                                    <v-col cols="4">
                                        <v-btn :color="selectedSkin === 'bordered' ? colorStore.color : 'transparent'" height="70" class="pa-0" variant="outlined" @click="selectSkin('bordered')">
                                            <v-card color="transparent" style="box-shadow: none;" class="d-flex align-center justify-center">
                                                <img
                                                    :src="getImageUrl('misc/skin-border.png')"
                                                    :style="{'height': '65px','object-fit': 'contain','background-color': theme == 'dark' ? 'darkslategray' : 'transparent'}"
                                                    class="d-flex align-center justify-center"
                                                />
                                            </v-card>
                                        </v-btn>
                                        <p class="text-caption">Bordered</p>
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
                            <v-list-item-title class="mt-5">Content</v-list-item-title>
                            <div class="pa-0 ma-0 mt-3">
                                <v-row>
                                    <v-col cols="4">
                                        <v-btn :color="selectedContent === 'Compact' ? colorStore.color : 'transparent'" height="70" class="pa-0" variant="outlined" @click="selectContent('Compact')">
                                            <v-card color="transparent" style="box-shadow: none;" class="d-flex align-center justify-center">
                                                <img
                                                    :src="getImageUrl('misc/Compact.png')"
                                                    :style="{'height': '65px','object-fit': 'contain','background-color': theme == 'dark' ? 'darkslategray' : 'transparent'}"
                                                    class="d-flex align-center justify-center"
                                                />
                                            </v-card>
                                        </v-btn>
                                        <p class="text-caption">Compact</p>
                                    </v-col>

                                    <v-col cols="4">
                                        <v-btn :color="selectedContent === 'Wide' ? colorStore.color : 'transparent'" height="70" class="pa-0" variant="outlined" @click="selectContent('Wide')">
                                            <v-card color="transparent" style="box-shadow: none;" class="d-flex align-center justify-center">
                                                <img
                                                    :src="getImageUrl('misc/Wide.png')"
                                                    :style="{'height': '65px','object-fit': 'contain','background-color': theme == 'dark' ? 'darkslategray' : 'transparent'}"
                                                    class="d-flex align-center justify-center"
                                                />
                                            </v-card>
                                        </v-btn>
                                        <p class="text-caption">Wide</p>
                                    </v-col>
                                </v-row>
                            </div>
                        </v-container>
                    </v-navigation-drawer>
                </v-app>
            </v-responsive>
        </v-layout>
    </div>
    <v-btn
        icon="mdi-arrow-up-bold"
        style="position: fixed;bottom:140px;right:20px;z-index: 999;" size="large"
        :color="colorStore.color"
        @click="goTo(0)"
    ></v-btn>
    <v-btn style="position: fixed;bottom:70px;right:20px;z-index: 999;" icon="mdi-cog-outline" size="large" class="rotate-animation setting_btn" :color="colorStore.color" @click.stop="toggleRightDrawer = !toggleRightDrawer"></v-btn>
</template>
<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref, watch, onMounted } from 'vue';
import axios from 'axios';
import { Store } from '@/store/index';
import $ from 'jquery';
import { useGoTo } from 'vuetify';

const goTo = useGoTo()
const colorStore = Store();
const theme = ref(colorStore.theme);
const selectedSkin = ref(colorStore.selectedSkin);
const selectedLayout = ref(colorStore.selectedLayout);
const selectedContent = ref(colorStore.selectedContent);
const selectedDirection = ref(colorStore.selectedDirection);
const toggleRightDrawer = ref(false);
const switchColor = ref(false);
const showColorPicker = ref(false);
const colorPicker = ref(colorStore.color);
const selectedColor = ref(localStorage.getItem('selectedColor') || 'grey-darken-3');
const colors = ref(['#0D47A1', '#B71C1C', '#1B5E20', '#4A148C']);
const items = ref([
        { label: "Todayfsaadddddddddddddddddddddddsaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaadddddffffffffffffffffffffffffffffffffffffffdddddddddddddd22222rr4rrrrrrrrrrrrrrrrrrr33", image: "CompanyProfile/google.png" },
        { label: "Tomorrowsdddddddddddddddddddddddddddddsdddddddddddd", image: "CompanyProfile/1736393563.jpg" },
        { label: "សាលាគុនខេត្តតាកែវ សាលាគុនខេត្តតាកែវ សាលាគុនខេត្តតាកែវ សាលាគុនខេត្តតាកែវ សាលាគុនខេត្តតាកែវ", image: "CompanyProfile/google.png" },
        { label: "Weekend", image: "CompanyProfile/1736393563.jpg" },
        { label: "Today", image: "CompanyProfile/google.png" },
        { label: "Tomorrow", image: "CompanyProfile/1736393563.jpg" },
        { label: "Yesterday", image: "CompanyProfile/google.png" },
      ]);
const company_profile = ref([]);

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
  () => colorStore.menuItem,
  (newItem) => {
    items.value = newItem.sort((a, b) => a.order - b.order);
  }
);

onMounted(()=>{
    getCompanyProfile();
});

async function getCompanyProfile() {
    try {
        const response = await axios.get('getCompanyProfile');
        if (response.data && Array.isArray(response.data)) {
            // Filter for `isUsed = 1`
            const filteredProfiles = response.data.filter(profile => profile.isUsed === 1);

            // Find the profile with the latest `updated_at`
            if (filteredProfiles.length > 0) {
                company_profile.value = filteredProfiles.reduce((latest, current) => {
                    return new Date(current.updated_at) > new Date(latest.updated_at) ? current : latest;
                });
            } else {
                company_profile.value = null; // No profiles meet the condition
            }

        } else {
            console.error('Unexpected response format:', response.data);
        }
    } catch (error) {
        console.error('Error fetching data', error);
    }
    console.log(company_profile.value)
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

function ChangeTheme(newTheme) {
    colorStore.setTheme(newTheme);
};

function selectSkin(skin) {
    colorStore.setSelectedSkin(skin);
};

function selectContent(content) {
    colorStore.setSelectedContent(content);
};

function toggleColorPicker() {
  showColorPicker.value = !showColorPicker.value;
};

function getImageUrl(name) {
    return new URL(`/resources/js/images/${name}`, import.meta.url).href
};

function getStorageImageUrl(name) {
    return new URL(`/storage/images/${name}`, import.meta.url).href
};
</script>
<style>
.grid-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); /* Responsive grid */
    gap: 0.5rem; /* Space between grid items */
}

.grid-item {
    position: relative;
    display: flex;
    justify-content: center;
    align-items: center;
    overflow: hidden;
    border-radius: 8px; /* Optional rounded corners */
    cursor: pointer;
}

.grid-item img {
    width: 50px; /* Make images responsive */
    height: auto; /* Maintain aspect ratio */
}

.grid-item .position-absolute {
    position: absolute; /* Keep logo overlay */
}

.text-clamp {
  display: -webkit-box;
  -webkit-line-clamp: 3; /* Limit to 3 rows */
  -webkit-box-orient: vertical;
  overflow: hidden;
  text-overflow: ellipsis; /* Add "..." for overflow */
  max-height: calc(1.5em * 3); /* Adjust based on your line height */
  font-size: 1.25rem;
}

</style>
<script>
    setTimeout(() => {
        //move button setting
        const button = document.querySelector('.setting_btn');
        let isDragging = false;
        let offsetX, offsetY;

        if(button != undefined){
            button.addEventListener('mousedown', (event) => {
                isDragging = true;
                // Calculate the initial offset of the mouse cursor from the button's position
                offsetX = event.clientX - button.getBoundingClientRect().left;
                offsetY = event.clientY - button.getBoundingClientRect().top;

                // Add a style to prevent the text selection during dragging
                document.body.style.userSelect = 'none';
            });

            document.addEventListener('mousemove', (event) => {
                if (isDragging) {
                    const x = event.clientX - offsetX;
                    const y = event.clientY - offsetY;

                    // Update the button's position based on the mouse movement
                    button.style.left = `${x}px`;
                    button.style.top = `${y}px`;
                }
            });

            document.addEventListener('mouseup', () => {
                isDragging = false;
                document.body.style.userSelect = '';
            });
        }

    }, 200);
</script>
