<template>
    <div>
        <v-layout class="rounded rounded-md">
            <v-responsive class="border rounded">
                <v-app :theme="theme" style="display: flex;align-items: center;justify-content: center;overflow-y: hidden;overflow-x: auto;">
                    <v-container elevation="10" :fluid="colorStore.selectedContent !== 'Compact'" class="pa-0 mb-5 mt-5 position-relative" style="min-width: 1320px;border-radius: 5px;box-shadow: rgba(0, 0, 0, 0.25) 0px 0.0625em 0.0625em, rgba(0, 0, 0, 0.25) 0px 0.125em 0.5em, rgba(255, 255, 255, 0.1) 0px 0px 0px 1px inset;">
                        <v-row class="pa-2 mb-1" style="height: 100px;display: flex;align-items: center;">
                            <v-col cols="2" class="" style="height: 100%;">
                                <v-card
                                    href="/"
                                    style="height: 100%;"
                                    variant="text"
                                >
                                    <v-img  v-if="company_profile !== null" :src="getStorageImageUrl('CompanyProfile/'+company_profile.logo)" style="height: 100%;border-radius: 5px;display: block;" alt="Company Logo"></v-img>
                                </v-card>
                            </v-col>
                            <v-col cols="8">
                                <v-tabs
                                    v-model="tab"
                                    next-icon="mdi-arrow-right-bold-box-outline"
                                    prev-icon="mdi-arrow-left-bold-box-outline"
                                    show-arrows
                                    center-active
                                    align-tabs="center"
                                    hide-slider
                                    :items="news_type_data"
                                    :style="{ '--selected-tab-color': colorStore.color }"
                                    >
                                    <template v-slot:tab="{ item }">
                                        <v-tab :value="item.id" :loading="loading" class="text-center" @click="tabChange(tab)">
                                            {{ $i18n.locale == "en" ? item.name_en : item.name_km }}
                                        </v-tab>
                                    </template>
                                </v-tabs>
                            </v-col>
                            <v-col cols="2" class="d-flex justify-center align-center">
                                <div class="d-flex justify-end">
                                    <v-menu offset-y>
                                        <template #activator="{ props }">
                                            <v-btn v-bind="props" icon variant="text" size="large">
                                                <v-icon>
                                                    mdi-translate
                                                </v-icon>
                                            </v-btn>
                                        </template>
                                        <v-list>
                                            <v-list-item :prepend-avatar="getImageUrl('logos/english_flag.jpg')" @click="ChangeLanguage('en')">
                                                <v-list-item-title>{{ $t('global.english') }}</v-list-item-title>
                                            </v-list-item>
                                            <v-list-item class="mt-3" :prepend-avatar="getImageUrl('logos/cambodia_flag.png')" @click="ChangeLanguage('kh')">
                                                <v-list-item-title>{{ $t('global.khmer') }}</v-list-item-title>
                                            </v-list-item>
                                        </v-list>
                                    </v-menu>
                                    <v-menu offset-y>
                                        <template #activator="{ props }">
                                            <v-btn v-bind="props" icon variant="text" size="large" class="mx-2">
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
                                <v-btn variant="text" icon="mdi-cog-outline" size="large">
                                    <v-icon>mdi-cog-outline</v-icon>
                                    <VMenu activator="parent" width="300" location="bottom end" offset="14px">
                                        <VList>
                                            <VListItem>
                                                <template #prepend>
                                                    <VListItemAction start>
                                                        <VAvatar color="primary" variant="tonal" size="60">
                                                            <VImg v-if="company_profile !== null" :src="getStorageImageUrl('CompanyProfile/'+company_profile.logo)" />
                                                        </VAvatar>
                                                    </VListItemAction>
                                                </template>
                                                <VListItemTitle class="font-weight-semibold">
                                                    {{ company_profile !== null ? company_profile.name_km : '' }}
                                                </VListItemTitle>
                                                <VListItemSubtitle>{{ company_profile !== null ? company_profile.name_en : '' }}</VListItemSubtitle>
                                            </VListItem>
                                            <VDivider class="my-2" />
                                            <VListItem link :href="route('register')">
                                                <template #prepend>
                                                    <VIcon class="me-2" icon="mdi-account-plus-outline" size="22" />
                                                </template>
                                                <VListItemTitle>{{ $t('global.register') }}</VListItemTitle>
                                            </VListItem>
                                            <VListItem link :href="route('login')">
                                                <template #prepend>
                                                    <VIcon class="me-2" icon="mdi-login" size="22" />
                                                </template>
                                                <VListItemTitle>{{ $t('global.login') }}</VListItemTitle>
                                            </VListItem>
                                        </VList>
                                    </VMenu>
                                </v-btn>
                            </v-col>
                        </v-row>

                        <v-card
                            class="mx-auto d-flex justify-center"
                            href="/"
                            max-width="100%"
                            height="150"
                            :color="colorStore.color"
                        >
                            <v-card-item v-if="company_profile !== null">
                                <v-img :src="getStorageImageUrl('CompanyProfile/'+company_profile.logo)" style="width: 140px;height: 140px;border-radius: 5px;" cover alt="Company Logo"></v-img>
                            </v-card-item>
                            <v-card-item class="text-center">
                                <v-card-title style="font-size: 3rem;">
                                    {{ company_profile !== null ? company_profile.name_km : '' }}
                                </v-card-title>

                                <v-card-title style="font-size: 2rem;">
                                    {{ company_profile !== null ? company_profile.name_en : '' }}
                                </v-card-title>
                            </v-card-item>
                        </v-card>

                        <v-carousel v-model="carousel" cycle class="carousel" height="400" show-arrows="hover" hide-delimiter-background :color="colorStore.color">
                            <v-carousel-item v-for="(chunk, index) in chunkedItems" :key="index">
                                <v-row align="center">
                                    <v-col cols="3" class="d-flex justify-center" v-for="(item, subIndex) in chunk" :key="subIndex">
                                        <v-card max-width="400" height="300" variant="text" @click="NewsInformationDetail(item)">
                                            <v-img
                                            :src="getStorageImageUrl('NewsImages/' + JSON.parse(item.image)[0])"
                                            width="400"
                                            style="border-radius: 5px;"
                                            ></v-img>
                                            <v-card-text class="text-center pa-2">
                                                <div class="font-weight-bold text-clamp">{{ $i18n.locale == "en" ? item.title_en : item.title_km }}</div>
                                            </v-card-text>
                                        </v-card>
                                    </v-col>
                                </v-row>
                            </v-carousel-item>
                        </v-carousel>

                        <v-main class="pa-5">
                            <v-row style="flex-wrap: nowrap;">
                                <v-col class="pa-0" style="min-width: 20%;max-width: 20%;">
                                    <v-card class="pa-0 bg-transparent" :style="{ border: `2px solid ${colorStore.color}`, borderRadius: '5px', height: '100%' }">
                                        <v-tabs
                                            v-model="tab_about_news"
                                            next-icon="mdi-arrow-right-bold-box-outline"
                                            prev-icon="mdi-arrow-left-bold-box-outline"
                                            show-arrows
                                            center-active
                                            align-tabs="start"
                                            direction="vertical"
                                            hide-slider
                                            :items="about_news_type_select_data"
                                            :style="{ '--selected-tab-color': colorStore.color }"
                                            >
                                            <template v-slot:tab="{ item }">
                                                <v-tab :value="item" :loading="loading" class="text-center" @click="tabAboutNewsChange(tab_about_news)">
                                                    {{ $i18n.locale == "en" ? item.about_news_name_en : item.about_news_name_km }}
                                                </v-tab>
                                            </template>
                                        </v-tabs>
                                    </v-card>
                                </v-col>
                                <v-col class="pt-0 pb-0" style="min-width: 60%;max-width: 60%;">
                                    <v-card v-if="tab_about_news_check == false" class="pa-3" color="transparent" :style="{ border: `2px solid ${colorStore.color}`, height: '100%', borderRadius: '5px' }">
                                        <div v-if="news_detail.length != 0">
                                            <v-card-title class="text-center" :style="{ borderRadius: '5px', backgroundColor: colorStore.color }">{{ $i18n.locale == "en" ? news_detail.title_en : news_detail.title_km }}</v-card-title>
                                            <v-divider class="border-opacity-100 my-5" :thickness="2" :color="colorStore.color"></v-divider>

                                            <v-card-item>
                                                <v-row class="d-flex justify-center">
                                                    <v-col
                                                        v-for="(img, index) in (() => {
                                                            try {
                                                                return JSON.parse(news_detail.image) || [];
                                                            } catch (e) {
                                                                return [];
                                                            }
                                                        })()"
                                                        :key="index"
                                                        cols="12"
                                                        sm="6"
                                                    >
                                                        <v-img
                                                            :src="getStorageImageUrl('NewsImages/' + img)"
                                                            width="100%"
                                                            style="border-radius: 5px;"
                                                        ></v-img>
                                                    </v-col>
                                                </v-row>
                                            </v-card-item>
                                            <v-card-item class="description">
                                                <!-- <QuillEditor ref="descriptionEditor" theme="snow" :readOnly="true" /> -->
                                                <div id="editor-container" ref="descriptionEditor" style="height: 100%"></div>
                                            </v-card-item>

                                            <v-divider class="border-opacity-100 my-5" :thickness="2" :color="colorStore.color"></v-divider>
                                            <v-card-title class="text-center mb-5" :style="{ borderRadius: '5px', backgroundColor: colorStore.color }">{{ $t('company_profile.location') }}</v-card-title>

                                            <GoogleMap :api-key="apiKey" style="width: 100%; height: 500px; border-radius: 5px; overflow: hidden;" :center="{ lat: Number(lat), lng: Number(lng) }" :zoom="15">
                                                <Marker v-if="!isNaN(lat) && !isNaN(lng)" :options="{
                                                    position: { lat: Number(lat), lng: Number(lng) }, }" />
                                            </GoogleMap>
                                        </div>
                                    </v-card>

                                    <v-card v-else class="pa-3" color="transparent" :style="{ border: `2px solid ${colorStore.color}`, height: '100%', borderRadius: '5px' }">
                                        <v-card-title class="text-center" :style="{ borderRadius: '5px', backgroundColor: colorStore.color }">{{ $i18n.locale == "en" ? news_detail.title_en : news_detail.title_km }}</v-card-title>
                                        <v-divider class="border-opacity-100 my-5" :thickness="2" :color="colorStore.color"></v-divider>

                                        <v-card-item class="description pa-0 ma-0">
                                            <!-- <QuillEditor ref="descriptionEditor" theme="snow" :readOnly="true" /> -->
                                            <div id="editor-container" ref="descriptionEditor" style="height: 100%"></div>
                                        </v-card-item>
                                    </v-card>
                                </v-col>
                                <v-col class="pa-0" style="min-width: 20%;max-width: 20%;">
                                    <v-card class="pa-0 bg-transparent" :style="{ border: `2px solid ${colorStore.color}`, borderRadius: '5px', height: '100%' }">
                                        <v-img
                                            v-for="(sponsor, index) in sponsor_data" :key="index"
                                            :src="getStorageImageUrl('SponsorImages/' + sponsor.image)"
                                            style="border-radius: 5px;margin: 5px;margin-bottom: 10px;"
                                        ></v-img>
                                    </v-card>
                                </v-col>
                            </v-row>

                            <v-row class="my-5">
                                <v-col cols="12" class="pa-0">
                                    <h6 class="Muol-Light p-3 text-white" :style="{ borderRadius: '5px', backgroundColor: colorStore.color }">{{ $t('global.important_video') }}</h6>
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
                                                    :src="getImageUrl('misc/skin-default.png')"
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
                                                    :src="getImageUrl('misc/skin-border.png')"
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
                            <v-list-item-title>{{ $t('drawer.content') }}</v-list-item-title>
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
                                        <p class="text-caption">{{ $t('drawer.compact') }}</p>
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
                                        <p class="text-caption">{{ $t('drawer.wide') }}</p>
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
import { Head, Link } from '@inertiajs/vue3';
import { ref, watch, onMounted, computed } from 'vue';
import axios from 'axios';
import { Store } from '@/store/index';
import $ from 'jquery';
import { useGoTo } from 'vuetify';
import { GoogleMap, Marker } from 'vue3-google-map';
import { useI18n } from 'vue-i18n';

const { locale } = useI18n();
const goTo = useGoTo();
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
const items = ref([]);
const company_profile = ref([]);
const news_type_data = ref([]);
const news_information_data = ref([]);
const about_news_type_data = ref([]);
const about_news_type_select_data = ref([]);
const about_news_description_data = ref([]);
const carousel = ref(0);
const tab = ref(null);
const tab_about_news = ref(null);
const tab_about_news_check = ref(false);
const news_detail = ref([]);
const apiKey = ref("AIzaSyBP3eRYvTPrrRRAEFCOOkcn0gpcjKzqBHM");
const lat = ref(10.9134214);
const lng = ref(104.5888426);
const sponsor_data = ref([]);
const descriptionEditor = ref(null);
const quill = ref(null);
const loading = ref(false);
const options = ref({
                    readOnly: false,
                    theme: 'snow',
                    modules: {
                        toolbar: [
                            ['bold', 'italic', 'underline', 'strike'],
                            ['blockquote', 'code-block'],
                            ['link', 'image', 'video', 'formula'],

                            [{ 'header': 1 }, { 'header': 2 }],
                            [{ 'list': 'ordered'}, { 'list': 'bullet' }, { 'list': 'check' }],
                            [{ 'script': 'sub'}, { 'script': 'super' }],
                            [{ 'indent': '-1'}, { 'indent': '+1' }],
                            [{ 'direction': 'rtl' }],

                            [{ 'size': ['small', false, 'large', 'huge'] }],
                            [{ 'header': [1, 2, 3, 4, 5, 6, false] }],

                            [{ 'color': [] }, { 'background': [] }],
                            [{ 'font': [] }],
                            [{ 'align': [] }],

                            ['clean']
                        ],
                        imageResize: {
                            displayStyles: {
                                backgroundColor: 'black',
                                border: 'none',
                                color: 'white'
                            },
                            modules: [ 'Resize', 'DisplaySize', 'Toolbar' ]
                        },
                    },
                });

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

const filteredNews = computed(() => {
    return Array.isArray(news_information_data.value)
        ? news_information_data.value.filter(item => item.news_type_id === tab.value)
        : [];
});

const chunkedItems = computed(() => {
    const chunkSize = 4;
    return filteredNews.value.reduce((acc, item, index) => {
        const chunkIndex = Math.floor(index / chunkSize);
        if (!acc[chunkIndex]) acc[chunkIndex] = [];
        acc[chunkIndex].push(item);
        return acc;
    }, []);
});

onMounted(async ()=>{
    quill.value = new Quill(descriptionEditor.value, options.value);
    getAboutNewsType();
    getCompanyProfile();
    getNewsType();
    getNewsInformation();
    getSponsor();
});

watch(chunkedItems, (newVal) => {
    if (newVal.length > 0) {
        autoClickFirstItem();
    }else{
        news_detail.value = [];
    }
});

function ChangeLanguage(lang) {
    locale.value = lang;
    localStorage.setItem('locale', lang);
};

const autoClickFirstItem = () => {
    if (chunkedItems.value.length > 0 && chunkedItems.value[0].length > 0) {
        const firstItem = chunkedItems.value[0][0];
        NewsInformationDetail(firstItem);
    }
};

function tabAboutNewsChange(val) {
    tab_about_news_check.value = true;
    if(news_detail.value) {
        getAboutNewsDescription(val.id, news_detail.value.id);
    }
};

async function getAboutNewsDescription(tab_about_news_id, news_detail_id) {
    try {
        loading.value = true;
        const response = await axios.get('getAboutNewsDescription', {
            params: {
                tab_about_news_id: tab_about_news_id,
                news_detail_id: news_detail_id,
            },
        });

        about_news_description_data.value = response.data;
        quill.value = new Quill(descriptionEditor.value, options.value);
        quill.value.root.innerHTML = null;
        if (descriptionEditor.value) {
            if(about_news_description_data.value[0] != undefined) {
                quill.value = new Quill(descriptionEditor.value, options.value);
                quill.value.root.innerHTML = about_news_description_data.value[0]?.description;
            }
        }
        loading.value = false;
    } catch (error) {
        console.error('Error fetching data', error);
    }
};

async function getSponsor() {
    try {
        const response = await axios.get('getSponsor');
        if (response.data && Array.isArray(response.data)) {
            const filtered = response.data.filter(acitve => acitve.isUsed === 1);

            if (filtered.length > 0) {
                sponsor_data.value = filtered;
            } else {
                sponsor_data.value = null;
            }
        }
    } catch (error) {
        console.error('Error fetching data', error);
    }
};

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
};

async function getNewsType() {
    try {
        const response = await axios.get('getNewsTypeGrid');
        if (response.data && Array.isArray(response.data)) {
            const filteredProfiles = response.data.filter(acitve => acitve.isUsed === 1);

            if (filteredProfiles.length > 0) {
                news_type_data.value = filteredProfiles.sort((a, b) => a.order - b.order);
                tab.value = news_type_data.value[0].id;
                tabChange(news_type_data.value[0].id);
            } else {
                news_type_data.value = [];
            }
        }
    } catch (error) {
        console.error('Error fetching data', error);
    }
};

async function getNewsInformation() {
    try {
        const response = await axios.get('getNewsInformation');
        if (response.data && Array.isArray(response.data)) {
            const filteredNews = response.data.filter(acitve => acitve.isUsed === 1);

            if (filteredNews.length > 0) {
                news_information_data.value = filteredNews;
            } else {
                news_information_data.value = null;
            }
        }
    } catch (error) {
        console.error('Error fetching data', error);
    }
};

async function getAboutNewsType() {
    try {
        const response = await axios.get('getAboutNewsType');
        if (response.data && Array.isArray(response.data)) {
            const filteredNews = response.data.filter(acitve => acitve.isUsed === 1);

            if (filteredNews.length > 0) {
                about_news_type_data.value = filteredNews.sort((a, b) => a.order - b.order);
            } else {
                about_news_type_data.value = null;
            }
        }
    } catch (error) {
        console.error('Error fetching data', error);
    }
};

function NewsInformationDetail(item) {
    news_detail.value = item;
    tab_about_news_check.value = false;
    tab_about_news.value = null;
    if (item.location) {
        const [latitude, longitude] = item.location.split(',').map(coord => parseFloat(coord.trim()));
        lat.value = latitude;
        lng.value = longitude;
    } else {
        console.error('Location is missing in the provided item');
    }

    if (descriptionEditor.value) {
        quill.value.root.innerHTML = item.description;
    }
};

async function tabChange(tabID) {
    try {
        loading.value = true;
        tab_about_news_check.value = false;
        const response = await axios.get('getAboutNewsTypeID', {
            params: {
                news_type_id: tabID,
            },
        });

        if (response.status === 200) {
            about_news_type_select_data.value = response.data;
            loading.value = false;
        }
        setTimeout(() => {
            tab_about_news.value = null;
        }, 100);
    } catch (error) {
        console.error('Error fetching data', error);
    }
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
.description .ql-toolbar {
    display: none;
}

.description .ql-container {
    border: unset;
    font-family: 'Battambang';
}

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

.v-tab-item--selected {
    background-color: var(--selected-tab-color);
    border-radius: 5px !important;
}

.carousel .v-responsive__content {
    display: flex;
    padding-left: 10px;
    padding-right: 10px;
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
