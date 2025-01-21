<template>
    <MainApp>
        <v-row>
            <v-col class="txtsearch" cols="12" style="height: fit-content;">
                <v-row style="display: flex;align-items: end;">
                    <v-col cols="12" md="6">
                        <v-text-field class="search" prepend-inner-icon="mdi-magnify" icon variant="outlined" density="compact" :label="$t('global.search')" v-model="search"></v-text-field>
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
                :title="editMode ? $t('global.update') : $t('global.create')"
            >
                <v-divider></v-divider>

                <v-card-text class="pa-0" style="max-height: 1000px;">
                    <form @submit.prevent="editMode ? update() : create()" enctype="multipart/form-data">
                        <v-card-text>
                            <v-row dense>
                                <v-col cols="12" md="6">
                                    <v-select v-model="form.about_news_type_id" variant="outlined" density="compact" :label="$t('menu.about_news_type')+'*'" :items="about_news_type_data" item-title="about_news_name_en" item-value="id" :error-messages="errorMessage.about_news_type_id">
                                        <template v-slot:item="{ item, index }">
                                            <v-list-item
                                                :title="item.raw.about_news_name_en + ' -> ' + item.raw.name_en"
                                                @click="getSelectChangeAboutNewsType(item.value), form.about_news_type_id = item.value"
                                            >
                                            </v-list-item>
                                        </template>
                                    </v-select>
                                </v-col>
                                <v-col cols="12" md="6">
                                    <v-select v-model="form.news_information_id" :loading="loading" variant="outlined" density="compact" :label="$t('menu.news_information')+'*'" :items="news_information_data" item-title="title_en" item-value="id" :error-messages="errorMessage.news_information_id"></v-select>
                                </v-col>
                                <v-col cols="12" style="min-height: 500px;">
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
                        :text="$t('global.close')"
                        color="red"
                        @click="dialog = false, errorMessage = '', form.reset(), editMode = false"
                    ></v-btn>

                    <v-btn
                        color="primary"
                        :text="$t('global.save')"
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
import Quill from 'quill';

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
                about_news_type_id: null,
                news_information_id: null,
                description: null,
            }),
            quill: null,
            about_news_type_data: [],
            news_information_data: [],
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
        }
    },

    computed: {
        headers(){
            return[
                { title: this.$t('menu.about_news_type'), align: 'start', key: 'about_news_name_en' },
                { title: this.$t('menu.news_information'), align: 'start', key: 'title_en' },
                { title: this.$t('global.action'), key: 'action', align: 'center' },
            ]
        }
    },

    watch: {
        dialog(newVal) {
            if(newVal == true) {
                setTimeout(() => {
                    this.quill = new Quill(this.$refs.description, this.options);
                    if(this.editMode == true){
                        if (this.form.description) {
                            this.quill.root.innerHTML = this.form.description;
                        }
                    }
                }, 100);
            }
        }
    },

    mounted() {
        this.getAboutNewsDescriptionGrid();
        this.getAboutNewsType();
    },

    methods: {
        async getSelectChangeAboutNewsType(id) {
            try {
                this.loading = true;
                await axios.get('about_news_description/getNewsInformationCombo', {
                        params: { news_type: id },
                    }
                ).then((response)=>{
                    this.news_information_data = response.data;
                    this.loading = false;
                }).catch((e)=>{
                    console.error(e);
                });
            } catch (error) {
                console.error('Error fetching data', error);
            }
        },

        async getAboutNewsDescriptionGrid() {
            try {
                const response = await axios.get('about_news_description/getAboutNewsDescriptionGrid', {
                        params: {},
                    }
                );
                this.item = response.data;
                this.loading = false;
            } catch (error) {
                console.error('Error fetching data', error);
            }
        },

        async getAboutNewsType() {
            try {
                const response = await axios.get('about_news_type/getAboutNewsType');
                this.about_news_type_data = response.data;
                this.loading = false;
            } catch (error) {
                console.error('Error fetching data', error);
            }
        },

        create() {
            this.errorMessage = {};
            if (this.form.about_news_type_id === null) {
                this.errorMessage.about_news_type_id = "About News Type is required";
            }
            if (this.form.news_information_id === null) {
                this.errorMessage.news_information_id = "News Information is required";
            }

            const isDuplicate = this.item.some(item => {
                if(item.about_news_type_id === this.form.about_news_type_id){
                    this.errorMessage.about_news_type_id = "About News Type is duplicate";
                    return true;
                }
                if(item.news_information_id === this.form.news_information_id){
                    this.errorMessage.news_information_id = "News Information is duplicate";
                    return true;
                }
                return false;
            });

            if (Object.keys(this.errorMessage).length > 0 || isDuplicate) {
                return;
            }

            this.form.description = this.quill.root.innerHTML;
            this.form.post('about_news_description/create', {
                onSuccess: () => {
                    this.getAboutNewsDescriptionGrid();
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
            if (this.form.about_news_type_id === null) {
                this.errorMessage.about_news_type_id = "About News Type is required";
            }
            if (this.form.news_information_id === null) {
                this.errorMessage.news_information_id = "News Information is required";
            }

            const isDuplicate = this.item.some(item => {
                if(item.id != this.form.id) {
                    if(item.about_news_type_id === this.form.about_news_type_id){
                        this.errorMessage.about_news_type_id = "About News Type is duplicate";
                        return true;
                    }
                    if(item.news_information_id === this.form.news_information_id){
                        this.errorMessage.news_information_id = "News Information is duplicate";
                        return true;
                    }
                }
                return false;
            });

            if (Object.keys(this.errorMessage).length > 0 || isDuplicate) {
                return;
            }

            this.form.description = this.quill.root.innerHTML;
            this.form.post('about_news_description/update', {
                onSuccess: () => {
                    this.getAboutNewsDescriptionGrid();
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
        },

        confirmDelete(del) {
            if(del === true){
                this.form.post('about_news_description/delete', {
                    onSuccess: () => {
                        this.getAboutNewsDescriptionGrid();
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
            this.form.about_news_type_id = data.about_news_type_id;
            this.form.news_information_id = data.news_information_id;
            this.form.description = data.description;
        }
    }
}
</script>
