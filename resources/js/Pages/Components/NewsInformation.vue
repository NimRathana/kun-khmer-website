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
                            width="200"
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

        <v-dialog v-model="dialog" max-width="1200" persistent scrollable>
            <v-card
                :prepend-icon="editMode?'mdi-pen':'mdi-plus'"
                :title="editMode?'Update':'Create'"
            >
                <v-divider></v-divider>

                <v-card-text class="pa-0" style="max-height: 700px;">
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
                                            <v-checkbox hide-details label="Used" v-model="form.isUsed" color="primary" class="mb-3"></v-checkbox>
                                        </v-col>
                                        <v-col cols="12" sm="6">
                                            <v-text-field variant="outlined" density="compact" label="Url Video" v-model="form.url_video"></v-text-field>
                                        </v-col>

                                        <v-col cols="12" sm="6">
                                            <v-text-field variant="outlined" density="compact" label="Latitude" v-model="lat"></v-text-field>
                                        </v-col>
                                        <v-col cols="12" sm="6">
                                            <v-text-field variant="outlined" density="compact" label="Longitude" v-model="lng"></v-text-field>
                                        </v-col>
                                    </v-row>
                                </v-col>
                                <v-col cols="12" md="4" style="display: flex; justify-content: center; align-items: center; text-align: center; flex-direction: column;">
                                    <!-- Image Upload Section -->
                                    <v-row style="display: flex; justify-content: center; width: 100%;">
                                        <v-col cols="12" style="display: flex; flex-direction: column; align-items: center;">
                                            <v-sheet
                                                class="pa-2"
                                                height="250"
                                                max-height="250"
                                                width="90%"
                                                :elevation="5"
                                                rounded
                                                style="display: flex; justify-content: center; align-items: center; flex-wrap: wrap; overflow: auto;"
                                                >
                                                <!-- Display Previews of Cropped Images -->
                                                <template v-if="croppedImages.length > 0">
                                                    <div
                                                    v-for="(cropped, index) in croppedImages"
                                                    :key="index"
                                                    style="margin: 5px; display: flex; justify-content: center; align-items: center;"
                                                    >
                                                    <img
                                                        v-if="cropped !== undefined"
                                                        :src="cropped"
                                                        alt="Cropped Image"
                                                        style="width: 100%; min-height: 100px; object-fit: contain; border: 1px solid #ccc; border-radius: 5px;"
                                                    />
                                                    </div>
                                                </template>

                                                <!-- Fallback if no cropped images -->
                                                <span v-else>No Cropped Images</span>
                                            </v-sheet>

                                            <!-- File input for uploading images -->
                                            <v-file-input
                                                multiple
                                                style="display: none;"
                                                ref="fileInput"
                                                accept="image/*"
                                                @change="handleFileChange"
                                            ></v-file-input>

                                            <v-btn class="mt-5" @click="triggerFileInput" :color="colorStore.color">Upload</v-btn>
                                        </v-col>
                                    </v-row>
                                </v-col>
                                <v-col cols="12" class="my-3">
                                    <!-- Cropping Section -->
                                    <v-slide-group
                                        v-if="imagePreviews.length > 0"
                                        show-arrows
                                        style="width: 100%;"
                                    >
                                        <v-slide-group-item
                                            v-for="(image, index) in imagePreviews"
                                            :key="index"
                                            :value="index"
                                        >
                                            <v-sheet
                                                class="mr-3 bg-success"
                                                elevation="5"
                                                rounded="lg"
                                                style="display: flex; justify-content: center; align-items: center;width: 400px;overflow: hidden;"
                                            >
                                                <v-container :style="{ 'background-color': colorStore.color }">
                                                    <v-row>
                                                        <v-col cols="1" class="pa-0 pl-1 ma-0" style="display: flex;flex-direction: column;justify-content: space-evenly;align-items: center;">
                                                            <v-icon @click="zoom(index, 2)">mdi-magnify-plus</v-icon>
                                                            <v-icon @click="zoom(index, 0.5)">mdi-magnify-minus</v-icon>
                                                            <v-icon @click="move(index, 0, -10)">mdi-arrow-up-thin</v-icon>
                                                            <v-icon @click="move(index, -10, 0)">mdi-arrow-left-thin</v-icon>
                                                            <v-icon @click="move(index, 10, 0)">mdi-arrow-right-thin</v-icon>
                                                            <v-icon @click="move(index, 0, 10)">mdi-arrow-down-thin</v-icon>
                                                        </v-col>
                                                        <v-col cols="10">
                                                            <cropper
                                                                ref="cropper"
                                                                :src="getImageSource(image, index)"
                                                                :stencil-props="{
                                                                    movable: true,
                                                                    resizable: true,
                                                                }"
                                                                @error="error"
                                                                @change="updateCroppedImage($event, index)"
                                                                :stencil-size="{
                                                                    width: 200,
                                                                    height: 100
                                                                }"
                                                                :transitions="true"
                                                                image-restriction="stencil"
                                                                :auto-zoom="true"
                                                                :default-size="defaultSize"
                                                                style="height: 250px;width: 400px;overflow: hidden;border-radius: 10px;"
                                                            ></cropper>
                                                        </v-col>
                                                        <v-col cols="1" class="pa-0 pr-1 ma-0" style="display: flex;flex-direction: column;justify-content: space-evenly;align-items: center;">
                                                            <v-icon @click="flip(index, 1, 0)">mdi-flip-horizontal</v-icon>
                                                            <v-icon @click="flip(index, 0, 1)">mdi-flip-vertical</v-icon>
                                                            <v-icon @click="rotate(index, 90)">mdi-rotate-right</v-icon>
                                                            <v-icon @click="rotate(index, -90)">mdi-rotate-left</v-icon>
                                                            <v-icon @click="center(index)">mdi-image-filter-center-focus</v-icon>
                                                            <v-icon @click="download(index)">mdi-progress-download</v-icon>
                                                        </v-col>
                                                    </v-row>
                                                    <v-row>
                                                        <v-col cols="12" style="display: flex;justify-content: space-evenly;">
                                                            <v-btn color="red" @click="reset(index)">Reset</v-btn>
                                                            <v-btn color="warning" @click="crop(index)">Crop</v-btn>
                                                            <v-btn color="primary" @click="browse(index)">Browe</v-btn>
                                                        </v-col>
                                                    </v-row>
                                                </v-container>
                                                <input
                                                    type="file"
                                                    :ref="'fileInput' + index"
                                                    accept="image/*"
                                                    style="display: none;"
                                                    @change="handleFileChangeByIndex($event, index)"
                                                >
                                            </v-sheet>
                                        </v-slide-group-item>
                                    </v-slide-group>
                                </v-col>
                                <v-col cols="8" class="d-flex align-center">
                                    <input id="txtlocation" type="text" placeholder="Enter a location" style="width: 100%; padding: 10px;" @input="changeLocation" />
                                    <!-- <v-text-field id="txtlocation" hide-details variant="outlined" density="compact" label="Location" v-model="form.location" @input="changeLocation"></v-text-field> -->
                                    <v-card-text style="flex: unset;">or</v-card-text>
                                    <v-btn border prepend-icon="mdi-crosshairs-gps" :loading="loading" @click="getCurrentPosition()">Use my current location</v-btn>
                                </v-col>
                                <v-col cols="12" class="mt-2">
                                    <!-- <GoogleMap :api-key="apiKey" style="width: 100%; height: 400px; border-radius: 5px; overflow: hidden;" :center="{ lat: Number(lat), lng: Number(lng) }" :zoom="15" animation="bounce">
                                        <Marker v-if="!isNaN(lat) && !isNaN(lng)" :options="{
                                            position: { lat: Number(lat), lng: Number(lng) }, }" />
                                    </GoogleMap> -->
                                    <div id="map" style="width: 100%; height: 400px; border-radius: 5px; overflow: hidden;"></div>
                                </v-col>
                                <v-col cols="12" class="mt-2" style="min-height: 400px;">
                                    <!-- <v-textarea variant="outlined" density="compact" label="Description" v-model="form.description"></v-textarea> -->
                                    <!-- <QuillEditor ref="description" toolbar="full" :options="options" /> -->
                                    <div id="editor-container" ref="description" style="height: 100%"></div>
                                </v-col>
                            </v-row>
                        </v-card-text>
                    </form>
                </v-card-text>

                <v-divider class="mt-3"></v-divider>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                        text="Close"
                        color="red"
                        @click="handleClose"
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
import { Loader } from '@googlemaps/js-api-loader';
import Quill from 'quill';
import { Cropper } from 'vue-advanced-cropper'
import 'vue-advanced-cropper/dist/style.css';
import 'vue-advanced-cropper/dist/theme.compact.css';

export default {
    components: { MainApp, GoogleMap, Marker, Cropper },
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
                // { title: 'Description', key: 'description', align: 'start' },
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
                image: [],
                url_video: '',
                location: '',
                description: '',
                isUsed: false,
                image_delete: [],
            }),
            imageUrl: [],
            news_type_data: [],
            // apiKey: 'AIzaSyAHv9WrtrdTEAJGZXJlIGmefJwZzzyBnmw',
            apiKey: 'AIzaSyDj5ak7LYutPoHkpGNrPks3fMDc9sToYhI',
            lat: 10.9134214,
            lng: 104.5888426,
            quill: null,
            options: {
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
            },
            imagePreviews: [],
            croppedImages: [],
            originalImages: [],
            defaultSize({ imageSize, visibleArea }) {
                return {
                    width: (visibleArea || imageSize).width,
                    height: (visibleArea || imageSize).height,
                };
            }
        }
    },

    mounted() {
        this.getNewsInformationGrid();
        this.getNewsType();
        this.form.location = `${this.lat}, ${this.lng}`;
    },

    watch: {
        dialog(newVal){
            if(newVal == true){
                const loader = new Loader({
                    apiKey: this.apiKey,
                    libraries: ['places'],
                });
                loader.load()
                .then(() => {
                    this.initMap();
                })
                .catch((error) => {
                    console.error("Error loading Google Maps API:", error);
                });
                setTimeout(() => {
                    this.quill = new Quill(this.$refs.description, this.options);
                    if(this.editMode == true){
                        if (this.form.description) {
                            this.quill.root.innerHTML = this.form.description;
                        }
                    }
                }, 100);
            }
        },
        lat(newVal, oldVal){
            this.form.location = `${newVal}, ${this.lng}`;
            const map = new google.maps.Map(document.getElementById("map"), {
                center: { lat: Number(newVal), lng: Number(this.lng) },
                zoom: 15,
            });

            // Create the marker
            const marker = new google.maps.Marker({
                position: { lat: Number(newVal), lng: Number(this.lng) },
                map: map,
                animation: google.maps.Animation.BOUNCE,
                draggable: true,
                // icon: '@/images/logos/google.png'
            });
        },
        lng(newVal, oldVal){
            this.form.location = `${this.lat}, ${newVal}`;
            const map = new google.maps.Map(document.getElementById("map"), {
                center: { lat: Number(this.lat), lng: Number(newVal) },
                zoom: 15,
            });

            // Create the marker
            const marker = new google.maps.Marker({
                position: { lat: Number(this.lat), lng: Number(newVal) },
                map: map,
                animation: google.maps.Animation.BOUNCE,
                draggable: true,
                // icon: '@/images/logos/google.png'
            });
        }
    },

    methods: {
        getImageSource(image, index) {
            if (image instanceof File) {
                // If image is a File object, convert it to base64
                const reader = new FileReader();
                reader.onloadend = () => {
                    // Update imagePreviews with base64 string when the conversion is complete
                    this.imagePreviews[index] = reader.result; // Or handle accordingly
                };
                reader.readAsDataURL(image);
                return reader.result; // This is the base64 string
            }
            return image; // Return the string if it's already a base64 or URL
        },

        zoom(index, val) {
			this.$refs.cropper[index].zoom(val);
		},

        move(index, val1, val2) {
            this.$refs.cropper[index].move(val1, val2);
        },

        flip(index, x, y) {
			const { image } = this.$refs.cropper[index].getResult();
			if (image.transforms.rotate % 180 !== 0) {
				this.$refs.cropper[index].flip(!x, !y);
			} else {
				this.$refs.cropper[index].flip(x, y);
			}
		},

        rotate(index, angle) {
			this.$refs.cropper[index].rotate(angle);
		},

        center(index) {
			this.$refs.cropper[index].setCoordinates(({ coordinates, imageSize }) => ({
				left: imageSize.width / 2 - coordinates.width / 2,
				top: imageSize.height / 2 - coordinates.height / 2,
			}));
		},

        download(index) {
            const cropper = this.$refs.cropper[index];
            const croppedImage = cropper.getResult().canvas;

            // Convert to blob and download
            croppedImage.toBlob((blob) => {
                const link = document.createElement('a');
                link.href = URL.createObjectURL(blob);
                link.download = `cropped-image-${index}.png`;
                link.click();
            });
        },

        crop(index) {
            try{
                const { coordinates, canvas, } = this.$refs.cropper[index].getResult();

                if (canvas) {

                    const croppedImage = canvas.toDataURL();

                    this.croppedImages[index] = croppedImage;

                    // Update the preview for the cropped image
                    this.imagePreviews.splice(index, 1, croppedImage);

                    // Update the form's image data
                    this.form.image[index] = croppedImage;
                }
            }catch(e){
                console.log(e);
            }
		},

        reset(index) {
            // Option 1: Revert to the original image if stored
            if (this.originalImages && this.originalImages[index]) {
                this.imagePreviews.splice(index, 1, this.originalImages[index]);
            } else {
                // Option 2: Clear the image preview
                this.imagePreviews.splice(index, 1, null);
            }

            // Clear the cropper reference (optional)
            this.$refs.cropper[index].reset();
        },

        browse(index) {
            const fileInput = this.$refs[`fileInput${index}`];
            if (fileInput) fileInput[0].click();
        },

        handleFileChangeByIndex(event, index) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.imagePreviews[index] = e.target.result;
                    this.originalImages[index] = e.target.result;
                };
                reader.readAsDataURL(file);
            }
            event.target.value = null; // Reset the file input
        },

        handleFileChange(event) {
            const files = Array.from(event.target.files);
            this.imagePreviews = [];

            files.forEach((file) => {
                const reader = new FileReader();

                reader.onload = (e) => {
                    this.imagePreviews.push(e.target.result);
                    this.originalImages.push(e.target.result);
                };

                reader.onerror = (error) => {
                    console.error("Error reading file:", error);
                };

                reader.readAsDataURL(file);
            });

            this.form.image = this.imagePreviews;
        },

        error() {
			console.log('There is error during image loading');
		},

		updateCroppedImage(event, index) {
            const canvas = event.canvas;
            if (canvas) {
                this.croppedImages[index] = canvas.toDataURL('image/jpeg');
            }
        },

        convertImageToFile(index) {
            try {
                // Ensure cropped image exists at the provided index
                if (!this.croppedImages || !this.croppedImages[index]) {
                    console.error(`No cropped image found at index ${index}`);
                    return;
                }

                // Extract base64 string and MIME type from the cropped image
                const base64String = this.croppedImages[index];
                const byteString = atob(base64String.split(',')[1]);
                const mimeString = base64String.split(',')[0].split(':')[1].split(';')[0];

                // Create ArrayBuffer from the byteString
                const ab = new ArrayBuffer(byteString.length);
                const ia = new Uint8Array(ab);
                for (let i = 0; i < byteString.length; i++) {
                    ia[i] = byteString.charCodeAt(i);
                }

                // Create Blob from ArrayBuffer
                const blob = new Blob([ab], { type: mimeString });

                // Create a File object from the Blob
                const file = new File([blob], `croppedImage_${index}.png`, { type: mimeString });

                // Update form data with the newly created file
                this.form.image[index] = file;

                const reader = new FileReader();
                reader.onloadend = () => {
                    this.croppedImages[index] = reader.result;  // Update the src with base64 string
                    this.imagePreviews[index] = reader.result;
                };
                reader.readAsDataURL(file);

            } catch (error) {
                console.error('Error converting image to file:', error);
            }
        },

        handleClose() {
            this.dialog = false;
            this.errorMessage = '';
            this.form.reset();
            this.editMode = false;
            this.imageUrl = null;
            this.lat = 10.9134214;
            this.lng = 104.5888426;
            this.imagePreviews = [];
            this.croppedImages = [];
            this.originalImages = [];
        },

        changeLocation() {
            const inputElement = document.getElementById('txtlocation');
            const autocomplete = new google.maps.places.Autocomplete(inputElement, {
                //types: ['geocode'], // Suggest addresses only
                //fields: ['place_id', 'geometry', 'name'], // Fields to return
                types: ['(cities)']
            });
            // google.maps.event.addListener(autocomplete, 'place_changed', function () {
            //     var place = autocomplete.getPlace();
            //     document.getElementById('txtlocation').value = place.name;
            // });
        },

        initMap() {
            const map = new google.maps.Map(document.getElementById("map"), {
                center: { lat: this.lat, lng: this.lng },
                zoom: 15,
            });

            // Create the marker
            const marker = new google.maps.Marker({
                position: { lat: this.lat, lng: this.lng },
                map: map,
                animation: google.maps.Animation.BOUNCE,
                draggable: true,
                // icon: '@/images/logos/google.png'
            });

            google.maps.event.addListener(marker, 'dragend', function() {
                const position = marker.getPosition();
                const latitude = position.lat();
                const longitude = position.lng();

                this.lat = latitude;
                this.lng = longitude;
                this.form.location = `${latitude}, ${longitude}`;
            }.bind(this));


            // var infowindow = new google.maps.InfoWindow({
            //     content: `
            //         <div style="background-color: #fff; color: #333; padding: 10px; border-radius: 5px; width: 200px;">
            //             <v-card-text>Latitude: ${this.lat}</v-card-text>
            //             <v-card-text>Longitude: ${this.lng}</v-card-text>
            //         </div>
            //     `,
            // })
            // infowindow.open(map, marker);
        },

        async getCurrentPosition() {
            try {
                this.loading = true;
                const coordinates = await this.getLocation();
                this.lat = coordinates.lat;
                this.lng = coordinates.lng;
                this.form.location = `${this.lat}, ${this.lng}`;
                this.initMap();
                this.loading = false;
            } catch (error) {
                this.loading = false;
                this.snackbar = true;
                this.errorMessage = error;
                this.lat = 40.7128;
                this.lng = -74.0060;
                this.form.location = `${this.lat}, ${this.lng}`;
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

            for(var i = 0; i < this.croppedImages.length; i++){
                this.convertImageToFile(i);
            }

            this.form.description = this.quill.root.innerHTML;
            this.form.post('news_information/create', {
                onSuccess: () => {
                    this.getNewsInformationGrid();
                    this.form.reset();
                    this.lat = 10.9134214;
                    this.lng = 104.5888426;
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

            for(var i = 0; i < this.croppedImages.length; i++){
                this.convertImageToFile(i);
            }

            this.form.description = this.quill.root.innerHTML;
            this.form.post('news_information/update', {
                onSuccess: () => {
                    this.getNewsInformationGrid();
                    this.form.reset();
                    this.lat = 10.9134214;
                    this.lng = 104.5888426;
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
                        this.lat = 10.9134214;
                        this.lng = 104.5888426;
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
                    this.imagePreviews = this.imageUrl;
                    this.form.image_delete = images;
                } catch (error) {
                    console.error("Error parsing images:", error);
                }
            }
        },

        triggerFileInput() {
            this.$refs.fileInput.$el.querySelector('input').click();
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
<style>
.ql-snow{
    border-radius: 5px;
}
.ql-container {
    font-family: 'Battambang';
}
div.ql-tooltip.ql-editing {
    left: 0px !important;
    top: 0px !important;
}
</style>
