<template>
    <MainApp>
        <v-row style="height: 100%;">
            <v-col class="head" cols="12" style="height: fit-content;">
                <v-row style="display: flex;align-items: end;">
                    <v-col cols="12" md="6">
                        <v-text-field class="search" prepend-inner-icon="mdi-magnify" icon variant="outlined" density="compact" label="search" v-model="search"></v-text-field>
                    </v-col>
                    <v-col cols="12" md="6">
                        <v-btn color="primary" style="float: right;text-align: center;" @click="dialog = true" size="small">
                            <v-icon>mdi-plus</v-icon>Add
                        </v-btn>
                    </v-col>
                </v-row>
            </v-col>

            <v-col cols="12" class="body" style="height: 100%;">
                <v-data-table
                    :headers="headers"
                    :items="item"
                    :search="search"
                    :items-per-page="itemsPerPage"
                    :loading="loading"
                    :total-items="totalItems"
                    @dblclick:row="editItem"
                    :server-items-length="totalItems"
                    :sticky="true"
                >
                    <template v-slot:loading>
                        <v-skeleton-loader type="table-row@10"></v-skeleton-loader>
                    </template>
                    <template v-slot:item.image="{ item }">
                        <div class="pa-2" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px;">
                            <v-img
                            v-for="(image, index) in parseImages(item.image)"
                            :key="index"
                            :src="getImageUrl(image)"
                            height="100"
                            width="100"
                            cover
                            style="border-radius: 5px;object-fit: contain; border: 1px solid #ccc;"
                            ></v-img>
                        </div>
                    </template>
                    <template v-slot:item.news_type_id="{ item }">
                        {{ item.name_en }}
                    </template>
                    <template v-slot:item.isUsed="{ item }">
                        <v-chip
                            :color="item.isUsed ? 'green' : 'red'"
                        >
                            {{ item.isUsed ? 'Active' : 'Inactive' }}
                        </v-chip>
                    </template>
                    <template v-slot:item.action="{ item }">
                        <v-icon @click="editItem($event, item)" color="primary">mdi-pencil</v-icon>
                        <v-icon @click="deleteMenu(item)" class="ml-2" color="red">mdi-delete</v-icon>
                    </template>
                </v-data-table>
            </v-col>
        </v-row>

        <v-dialog v-model="dialog" max-width="1000" persistent scrollable>
            <v-card
                :prepend-icon="editMode?'mdi-pen':'mdi-plus'"
                :title="editMode?'Update':'Create'"
            >
                <v-divider></v-divider>

                <v-card-text class="pa-0" style="max-height: 500px;">
                    <form @submit.prevent="editMode ? update() : create()" enctype="multipart/form-data">
                        <v-card-text>
                            <v-row dense>
                                <v-col cols="12" md="8">
                                    <v-row dense>
                                        <v-col cols="12" sm="6">
                                            <v-text-field variant="outlined" density="compact" label="Title English*" v-model="form.title_en" :error-messages="errorMessage.title_en"></v-text-field>
                                        </v-col>
                                        <v-col cols="12" sm="6">
                                            <v-text-field variant="outlined" density="compact" label="Title Khmer*" v-model="form.title_km" :error-messages="errorMessage.title_km"></v-text-field>
                                        </v-col>
                                        <v-col cols="12" sm="6">
                                            <v-select v-model="form.news_type_id" variant="outlined" density="compact" label="Select*" :items="news_type_data" item-title="name_en" item-value="id" :error-messages="errorMessage.news_type_id"></v-select>
                                        </v-col>
                                        <v-col cols="12" sm="6">
                                            <v-text-field variant="outlined" density="compact" label="Url Video" v-model="form.url_video"></v-text-field>
                                        </v-col>
                                        <v-col cols="12" class="d-flex">
                                            <v-textarea variant="outlined" density="compact" label="Description" v-model="form.description"></v-textarea>
                                            <v-checkbox hide-details label="Used" v-model="form.isUsed" color="primary"></v-checkbox>
                                        </v-col>
                                        <v-col cols="12" class="d-flex align-center">
                                            <v-text-field hide-details variant="outlined" density="compact" label="Location" v-model="form.location"></v-text-field>
                                            <v-card-text style="flex: unset;">or</v-card-text>
                                            <v-btn border prepend-icon="mdi-crosshairs-gps" :loading="loading" @click="getCurrentPosition()">Use my current location</v-btn>
                                        </v-col>
                                    </v-row>
                                </v-col>
                                <v-col cols="12" md="4" style="display: flex;justify-content: center;align-items: center;text-align: center;flex-direction: column;">
                                    <v-row style="display: flex;justify-content: center;width:100%;">
                                        <v-col cols="12" style="display: flex;flex-direction: column;align-items: center;">
                                            <v-sheet
                                            class="pa-2"
                                            height="285"
                                            max-height="285"
                                            width="90%"
                                            :elevation="5"
                                            rounded
                                            style="display: flex; justify-content: center; align-items: center; flex-wrap: wrap; overflow: auto;"
                                            >
                                            <template v-if="imageUrl && imageUrl.length > 0">
                                                <div
                                                v-for="(preview, index) in imageUrl"
                                                :key="index"
                                                style="margin: 5px; display: flex; justify-content: center; align-items: center;"
                                                >
                                                <img
                                                    :src="preview"
                                                    alt="Uploaded Image"
                                                    style="width: 100px; height: 100px; object-fit: contain; border: 1px solid #ccc; border-radius: 5px;"
                                                />
                                                </div>
                                            </template>

                                                <!-- Fallback if no images -->
                                                <span v-else>No Images</span>
                                            </v-sheet>

                                            <!-- File input for uploading an image -->
                                            <v-file-input multiple style="display: none;" ref="fileInput" accept="image/*" @change="FileChange"></v-file-input>

                                            <v-btn class="mt-5" @click="triggerFileInput" :color="colorStore.color">Upload</v-btn>
                                        </v-col>
                                    </v-row>
                                </v-col>
                                <v-col cols="12" class="mt-2">
                                    <GoogleMap :api-key="apiKey" style="width: 100%; height: 400px; border-radius: 5px; overflow: hidden;" :center="{ lat: Number(lat), lng: Number(lng) }" :zoom="15">
                                        <Marker v-if="!isNaN(lat) && !isNaN(lng)" :options="{
                                            position: { lat: Number(lat), lng: Number(lng) }, }" />
                                    </GoogleMap>
                                </v-col>
                            </v-row>
                        </v-card-text>
                    </form>
                </v-card-text>

                <v-divider></v-divider>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                        text="Close"
                        color="red"
                        @click="dialog = false, errorMessage = '', form.reset(), editMode = false, imageUrl = null"
                    ></v-btn>

                    <v-btn
                        color="primary"
                        text="Save"
                        @click="editMode ? update() : create()"
                    ></v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-dialog v-model="deleteDialog" width="auto">
            <v-card>
                <v-card-text style="display: flex; align-items: center; justify-content: center;">
                    <div style="display: flex;flex-direction: column;align-items: center; justify-content: center;">
                        <v-icon size="x-large" color="red">mdi-delete</v-icon>
                        <span class="my-3">Are you sure you want to delete?</span>
                        <v-card-actions>
                            <v-btn size="small"
                            text="No, cancel"
                            variant="flat"
                            flat
                            color="blue-grey-lighten-5"
                            @click="deleteDialog = false"
                            ></v-btn>

                            <v-btn
                            size="small"
                            color="red"
                            text="Yes, I'm sure"
                            variant="flat"
                            flat
                            @click="confirmDelete(true)"
                            ></v-btn>
                        </v-card-actions>
                    </div>
                </v-card-text>
            </v-card>
        </v-dialog>

        <v-snackbar
            v-model="snackbar"
            :color="colorStore.color"
            vertical
            >
            <v-card class="bg-transparent" variant="text">
                <v-card-text>{{ errorMessage.message }}</v-card-text>
            </v-card>

            <template v-slot:actions>
                <v-btn
                color="red"
                variant="text"
                @click="snackbar = false"
                >
                Close
                </v-btn>
            </template>
        </v-snackbar>

    </MainApp>
</template>

<script>
import MainApp from '../MainApp.vue';
import axios from 'axios';
import { useForm } from '@inertiajs/vue3';
import { GoogleMap, Marker } from 'vue3-google-map';
import { Store } from '@/store/index';
import VueGoogleAutocomplete from "vue-google-autocomplete";

export default {
    components: { MainApp, GoogleMap, Marker, VueGoogleAutocomplete },
    data() {
        return {
            colorStore: Store(),
            snackbar: false,
            item: [],
            deleteDialog: false,
            editMode: false,
            errorMessage: '',
            search: null,
            dialog: false,
            itemsPerPage: 5,
            headers: [
                { title: 'News Type', key: 'news_type_id', align: 'start' },
                { title: 'Title EN', key: 'title_en', align: 'start' },
                { title: 'Title KM', key: 'title_km', align: 'start' },
                { title: 'Image', key: 'image', align: 'start' },
                { title: 'Url Video', key: 'url_video', align: 'start' },
                { title: 'Location', key: 'location', align: 'start' },
                { title: 'Description', key: 'description', align: 'start' },
                { title: 'Used', key: 'isUsed', align: 'start' },
                { title: 'Actions', key: 'action', align: 'center' },
            ],
            loading: false,
            totalItems: 0,
            form: useForm({
                id: '',
                news_type_id: null,
                title_en: '',
                title_km: '',
                image: '',
                url_video: '',
                location: '',
                description: '',
                isUsed: false,
                image_delete: [],
            }),
            imageUrl: [],
            news_type_data: [],
            apiKey: 'AIzaSyAHv9WrtrdTEAJGZXJlIGmefJwZzzyBnmw',
            lat: 10.9134214,
            lng: 104.5888426,
            autocomplete: null,
        }
    },

    mounted() {
        this.getNewsInformationGrid();
        this.getNewsType();
        // this.getAddressForm();
    },

    watch: {

    },

    methods: {
        async getCurrentPosition() {
            try {
                this.loading = true;
                const coordinates = await this.getLocation();
                this.lat = coordinates.lat;
                this.lng = coordinates.lng;
                this.loading = false;
            } catch (error) {
                this.loading = false;
                this.snackbar = true;
                this.errorMessage = error;
                this.lat = 40.7128;
                this.lng = -74.0060;
            }
        },

        async getLocation() {
            return new Promise((resolve, reject) => {
                navigator.geolocation.getCurrentPosition(
                    (position) => resolve({ lat: position.coords.latitude, lng: position.coords.longitude }),
                    (error) => reject(error)
                );
            });
        },

        getAddressForm(){
            axios.get("http://maps.google.com/maps/api/geocode/json?latlng="+ this.lat + "," + this.lng + "&key=" + this.apiKey)
            .then(res=>{
                debugger
            }).catch(e=>{
                console.log(e);
            })
        },

        getNewsInformationGrid() {
            try {
                axios.get('news_information/getNewsInformationGrid')
                .then((response)=>{
                    this.item = response.data;
                    this.loading = false;
                }).catch((e)=>{
                    console.error(e);
                });

            } catch (error) {
                console.error('Error fetching data', error);
            }
        },

        async getNewsType() {
            try {
                const response = await axios.get('news_type/getNewsTypeGrid');
                if (response.data && Array.isArray(response.data)) {
                    const filtered = response.data.filter(acitve => acitve.isUsed === 1);

                    if (filtered.length > 0) {
                        this.news_type_data = filtered;
                    } else {
                        this.news_type_data = null;
                    }
                }
                this.loading = false;
            } catch (error) {
                console.error('Error fetching data', error);
            }
        },

        create() {
            this.errorMessage = {};
            if (this.form.title_en === "") {
                this.errorMessage.title_en = "Title EN is required";
            }
            if (this.form.title_km === "") {
                this.errorMessage.title_km = "Title KM is required";
            }
            if (this.form.news_type_id === "" || this.form.news_type_id === null) {
                this.errorMessage.news_type_id = "News Type is required";
            }

            this.item.some(item => {
                if(item.title_en.toLowerCase() === this.form.title_en.toLowerCase()){
                    this.errorMessage.title_en = "Title EN is duplicate";
                    return true;
                }
                if(item.title_km.toLowerCase() === this.form.title_km.toLowerCase()){
                    this.errorMessage.title_km = "Title KM is duplicate";
                    return true;
                }
                return false;
            });

            if (Object.keys(this.errorMessage).length > 0) {
                return;
            }

            this.form.post('news_information/create', {
                onSuccess: () => {
                    this.getNewsInformationGrid();
                    this.form.reset();
                    this.dialog = false;
                    this.errorMessage = "";
                },
                onError: (errors) => {
                    // this.errorMessage = errors;
                },
            });
        },

        update() {
            this.errorMessage = {};
            if (this.form.title_en === "") {
                this.errorMessage.title_en = "Title EN is required";
            }
            if (this.form.title_km === "") {
                this.errorMessage.title_km = "Title KM is required";
            }
            if (this.form.news_type_id === "" || this.form.news_type_id === null) {
                this.errorMessage.news_type_id = "News Type is required";
            }

            this.item.some(item => {
                if(item.id != this.form.id) {
                    if(item.title_en.toLowerCase() === this.form.title_en.toLowerCase()){
                        this.errorMessage.title_en = "Title EN is duplicate";
                    }
                    if(item.title_km.toLowerCase() === this.form.title_km.toLowerCase()){
                        this.errorMessage.title_km = "Title KM is duplicate";
                    }
                }
            });

            if (Object.keys(this.errorMessage).length > 0) {
                return;
            }

            this.form.post('news_information/update', {
                onSuccess: () => {
                    this.getNewsInformationGrid();
                    this.form.reset();
                    this.dialog = false;
                    this.errorMessage = "";
                },
                onError: (errors) => {
                    // this.errorMessage = errors;
                },
            });
        },

        deleteMenu(item) {
            this.deleteDialog = true;
            this.form.id = item.id;
            this.form.image = item.image;
        },

        confirmDelete(del) {
            if(del === true){
                this.form.post('news_information/delete', {
                    onSuccess: () => {
                        this.getNewsInformationGrid();
                        this.form.reset();
                        this.deleteDialog = false;
                    },
                    onError: (errors) => {
                        // this.errorMessage = errors;
                    },
                });
            }
        },

        editItem(event, item) {
            let data = null;
            this.editMode = true;
            this.dialog = true;
            if(event.type === "dblclick"){
                data = item.item;
            } else {
                data = item;
            }
            this.form.id = data.id;
            this.form.title_en = data.title_en;
            this.form.title_km = data.title_km;
            this.form.news_type_id = data.news_type_id;
            this.form.url_video = data.url_video;
            this.form.location = data.location;
            this.form.description = data.description;
            this.form.isUsed = data.isUsed == 1 ? true : false;
            this.imageUrl = [];
            if (data.image) {
                try {
                    const images = this.parseImages(data.image); // Parse the image field
                    this.imageUrl = images.map((img) => this.getImageUrl(img)); // Generate URLs for each image
                    this.form.image_delete = images;
                } catch (error) {
                    console.error("Error parsing images:", error);
                }
            }
        },

        triggerFileInput() {
            this.$refs.fileInput.$el.querySelector('input').click();;
        },

        FileChange(event) {
            const files = event.target.files;
            if (files.length > 0) {
                this.form.image = [...files]; // Store all selected files in form data
                this.imageUrl = Array.from(files).map((file) =>
                URL.createObjectURL(file)
                ); // Generate previews for all selected files
            } else {
                this.imageUrl = [];
                console.error("No files selected or invalid files.");
            }
        },

        getImageUrl(name) {
            return new URL(`/storage/images/NewsImages/${name}`, import.meta.url).href
        },

        parseImages(imageField) {
            try {
                return Array.isArray(JSON.parse(imageField)) ? JSON.parse(imageField) : [imageField];
            } catch (error) {
                console.error("Error parsing image field:", error);
                return [];
            }
        },
    }
}
</script>
