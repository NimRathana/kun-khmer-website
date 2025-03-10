<template>
    <MainApp>
        <v-row>
            <v-col class="txtsearch" cols="12" style="height: fit-content;">
                <v-row style="display: flex;align-items: center;">
                    <v-col cols="12" md="6">
                        <v-text-field hide-details class="search" prepend-inner-icon="mdi-magnify" icon variant="outlined" density="compact" :label="$t('global.search')" v-model="search"></v-text-field>
                    </v-col>
                    <v-col cols="12" md="6">
                        <v-btn color="primary" style="float: right;text-align: center;" @click="dialog = true" size="small">
                            <v-icon>mdi-plus</v-icon>{{ $t('global.add') }}
                        </v-btn>
                    </v-col>
                </v-row>
            </v-col>

            <v-col cols="12">
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

        <v-dialog v-model="dialog" max-width="900">
            <v-card
                :prepend-icon="editMode?'mdi-pen':'mdi-plus'"
                :title="editMode ? $t('global.update') : $t('global.create')"
            >
                <v-divider></v-divider>

                <form @submit.prevent="editMode ? update() : create()" enctype="multipart/form-data">
                    <v-card-text>
                        <v-row dense>
                            <v-col cols="12" md="8">
                                <v-text-field variant="outlined" density="compact" :label="$t('sponsor.sponsor_name')+'*'" v-model="form.sponsor_name" :error-messages="errorMessage.sponsor_name"></v-text-field>
                                <v-text-field class="mt-5" variant="outlined" density="compact" :label="$t('sponsor.contact')+'*'" v-model="form.contact" :error-messages="errorMessage.contact"></v-text-field>
                                <v-checkbox hide-details :label="$t('global.used')" v-model="form.isUsed" color="primary"></v-checkbox>
                            </v-col>
                            <v-col cols="12" md="4" style="display: flex;justify-content: center;align-items: center;text-align: center;flex-direction: column;">
                                <v-row style="display: flex;justify-content: center;align-items: center;">
                                    <v-col cols="12">
                                        <v-sheet class="pa-2" :height="200" :width="200" :elevation="5" rounded style="display: flex;justify-content: center;align-items: center;">
                                            <!-- Display the uploaded image -->
                                            <img
                                                v-if="imageUrl"
                                                :src="imageUrl"
                                                alt="$t('global.no_image')"
                                                style="max-width: 100%; max-height: 100%; object-fit: contain;"
                                            />
                                            <span v-else>{{ $t('global.no_image') }}</span>
                                        </v-sheet>

                                        <!-- File input for uploading an image -->
                                        <v-file-input style="display: none;" ref="fileInput" accept="image/*" @change="FileChange"></v-file-input>

                                        <v-btn class="mt-5" @click="triggerFileInput" color="warning">{{ $t('global.choose_image') }}</v-btn>
                                    </v-col>
                                </v-row>
                            </v-col>
                        </v-row>
                    </v-card-text>

                    <v-divider></v-divider>

                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn
                            :text="$t('global.close')"
                            color="red"
                            @click="dialog = false, errorMessage = '', form.reset(), editMode = false, imageUrl = null"
                        ></v-btn>

                        <v-btn
                            color="primary"
                            :text="$t('global.save')"
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
                        <span class="my-3">{{ $t('global.are_you_sure_you_want_to_delete') }}?</span>
                        <v-card-actions>
                            <v-btn size="small"
                            :text="$t('global.no_cancel')"
                            variant="flat"
                            flat
                            color="blue-grey-lighten-5"
                            @click="deleteDialog = false"
                            ></v-btn>

                            <v-btn
                            size="small"
                            color="red"
                            :text="$t('global.yes_i_am_sure')"
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
            loading: true,
            totalItems: 0,
            form: useForm({
                id: '',
                sponsor_name: '',
                contact: '',
                image: '',
                isUsed: false,
                image_delete: null,
            }),
            imageUrl: null
        }
    },

    computed: {
        headers(){
            return[
                { title: this.$t('sponsor.sponsor_name'), align: 'start', key: 'sponsor_name' },
                { title: this.$t('sponsor.contact'), key: 'contact', align: 'start' },
                { title: this.$t('profile.photo'), key: 'image', align: 'start' },
                { title: this.$t('global.used'), key: 'isUsed', align: 'start' },
                { title: this.$t('global.action'), key: 'action', align: 'center' },
            ]
        }
    },

    mounted() {
        this.getSponsorGrid();
    },

    methods: {
        async getSponsorGrid() {
            try {
                const response = await axios.get('sponsor/getSponsorGrid');
                this.item = response.data;
                this.loading = false;
            } catch (error) {
                console.error('Error fetching data', error);
            }
        },

        create() {
            this.errorMessage = {};
            if (this.form.sponsor_name === "") {
                this.errorMessage.sponsor_name = "Name is required";
            }
            if (this.form.contact === "") {
                this.errorMessage.name_km = "Contact is required";
            }

            const isDuplicate = this.item.some(item => {
                if(item.sponsor_name.toLowerCase() === this.form.sponsor_name.toLowerCase() && item.contact.toLowerCase() === this.form.contact.toLowerCase()){
                    this.errorMessage.sponsor_name = "Name is duplicate";
                    this.errorMessage.contact = "Contact is duplicate";
                    return true;
                }
                return false;
            });

            if (Object.keys(this.errorMessage).length > 0 || isDuplicate) {
                return;
            }

            this.form.post('sponsor/create', {
                onSuccess: () => {
                    this.getSponsorGrid();
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
            if (this.form.sponsor_name === "") {
                this.errorMessage.sponsor_name = "Name is required";
            }
            if (this.form.contact === "") {
                this.errorMessage.name_km = "Contact is required";
            }

            const isDuplicate = this.item.some(item => {
                if(item.id != this.form.id) {
                    if(item.sponsor_name.toLowerCase() === this.form.sponsor_name.toLowerCase() && item.contact.toLowerCase() === this.form.contact.toLowerCase()){
                        this.errorMessage.sponsor_name = "Name is duplicate";
                        this.errorMessage.contact = "Contact is duplicate";
                        return true;
                    }
                }
                return false;
            });

            if (Object.keys(this.errorMessage).length > 0 || isDuplicate) {
                return;
            }

            this.form.post('sponsor/update', {
                onSuccess: () => {
                    this.getSponsorGrid();
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
                this.form.post('sponsor/delete', {
                    onSuccess: () => {
                        this.getSponsorGrid();
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
            this.form.sponsor_name = data.sponsor_name;
            this.form.contact = data.contact;
            this.imageUrl = this.getImageUrl(data.image);
            this.form.isUsed = data.isUsed == 1 ? true : false;
            this.form.image_delete = data.image;
        },

        triggerFileInput() {
            this.$refs.fileInput.$el.querySelector('input').click();
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
            return new URL(`/storage/images/SponsorImages/${name}`, import.meta.url).href
        },
    }
}
</script>
