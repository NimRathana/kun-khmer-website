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
                        <v-img
                            :src="getImageUrl(item.image)"
                            height="200"
                            width="200"
                            cover
                            style="margin: 10px;border-radius: 5px;"
                        ></v-img>
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

        <v-dialog v-model="dialog" max-width="1000">
            <v-card
                :prepend-icon="editMode?'mdi-pen':'mdi-plus'"
                :title="editMode?'Update':'Create'"
            >
                <v-divider></v-divider>

                <form @submit.prevent="editMode ? update() : create()" enctype="multipart/form-data">
                    <v-card-text>
                        <v-row dense>
                            <v-col cols="12" md="8">
                                <v-row>
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
                                    <v-col cols="12" sm="6">
                                        <v-textarea variant="outlined" density="compact" label="Description" v-model="form.description"></v-textarea>
                                        <v-checkbox hide-details label="Used" v-model="form.isUsed" color="primary"></v-checkbox>
                                    </v-col>
                                    <v-col cols="12" sm="6">
                                        <v-textarea variant="outlined" density="compact" label="Location" v-model="form.location"></v-textarea>
                                    </v-col>
                                </v-row>
                            </v-col>
                            <v-col cols="12" md="4" style="display: flex;justify-content: center;align-items: center;text-align: center;flex-direction: column;">
                                <v-row style="display: flex;justify-content: center;align-items: center;">
                                    <v-col cols="12">
                                        <v-sheet class="pa-2" :height="200" :width="200" :elevation="5" rounded style="display: flex;justify-content: center;align-items: center;">
                                            <!-- Display the uploaded image -->
                                            <img
                                                v-if="imageUrl"
                                                :src="imageUrl"
                                                alt="No Image"
                                                style="max-width: 100%; max-height: 100%; object-fit: contain;"
                                            />
                                            <span v-else>No Image</span>
                                        </v-sheet>

                                        <!-- File input for uploading an image -->
                                        <v-file-input style="display: none;" ref="fileInput" accept="image/*" @change="FileChange"></v-file-input>

                                        <v-btn class="mt-5" @click="triggerFileInput" color="warning">Upload</v-btn>
                                    </v-col>
                                </v-row>
                            </v-col>
                        </v-row>
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
                            type="submit"
                        ></v-btn>
                    </v-card-actions>
                </form>
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
    </MainApp>
</template>

<script>
import MainApp from '../MainApp.vue';
import axios from 'axios';
import { useForm } from '@inertiajs/vue3';

export default {
    components: { MainApp },
    data() {
        return {
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
            loading: true,
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
                image_delete: '',
            }),
            imageUrl: false,
            news_type_data: []
        }
    },

    mounted() {
        this.getNewsInformationGrid();
        this.getNewsType();
    },

    methods: {
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
            this.imageUrl = this.getImageUrl(data.image);
            this.form.image_delete = data.image;
        },

        triggerFileInput() {
            this.$refs.fileInput.$el.querySelector('input').click();;
        },

        FileChange(event) {
            const file = event.target.files[0];

            if (file) {
                this.form.image = file;
                const imagePreview = URL.createObjectURL(file);
                this.imageUrl = imagePreview;
            } else {
                this.imageUrl = null;
                console.error("No file selected or invalid file.");
            }
        },

        getImageUrl(name) {
            return new URL(`/storage/images/NewsImages/${name}`, import.meta.url).href
        }
    }
}
</script>
