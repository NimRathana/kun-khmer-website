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

        <v-dialog v-model="dialog" max-width="600">
            <v-card
                :prepend-icon="editMode?'mdi-pen':'mdi-plus'"
                :title="editMode ? $t('global.update') : $t('global.create')"
            >
                <v-divider></v-divider>

                <form @submit.prevent="editMode ? update() : create()" enctype="multipart/form-data">
                    <v-card-text>
                        <v-row dense>
                            <v-col cols="12" sm="6">
                                <v-text-field variant="outlined" density="compact" :label="$t('global.name_en')+'*'" v-model="form.about_news_name_en" :error-messages="errorMessage.about_news_name_en"></v-text-field>
                            </v-col>
                            <v-col cols="12" sm="6">
                                <v-text-field variant="outlined" density="compact" :label="$t('global.name_km')+'*'" v-model="form.about_news_name_km" :error-messages="errorMessage.about_news_name_km"></v-text-field>
                            </v-col>
                            <v-col cols="12" sm="6">
                                <v-select v-model="form.news_type_id" @update:modelValue="selectChange(form.news_type_id)" variant="outlined" density="compact" :label="$t('menu.news_type')+'*'" :items="news_type_data" item-title="name_en" item-value="id" :error-messages="errorMessage.news_type_id"></v-select>
                                <v-checkbox hide-details :label="$t('global.used')" v-model="form.isUsed" color="primary"></v-checkbox>
                            </v-col>
                            <v-col cols="12" sm="6">
                                <v-text-field variant="outlined" type="number" density="compact" :label="$t('global.order')" v-model="form.order" :disabled="editMode == true ? false : true" :error-messages="errorMessage.order"></v-text-field>
                            </v-col>
                        </v-row>
                    </v-card-text>

                    <v-divider></v-divider>

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
            headers: [
                { title: this.$t('global.name_en'), align: 'start', key: 'about_news_name_en' },
                { title: this.$t('global.name_km'), key: 'about_news_name_km', align: 'start' },
                { title: this.$t('menu.news_type'), key: 'name_en', align: 'start' },
                { title: this.$t('global.order'), key: 'order', align: 'start' },
                { title: this.$t('global.used'), key: 'isUsed', align: 'start' },
                { title: this.$t('global.action'), key: 'action', align: 'center' },
            ],
            loading: true,
            totalItems: 0,
            form: useForm({
                id: '',
                about_news_name_en: '',
                about_news_name_km: '',
                news_type_id: null,
                order: '',
                isUsed: false,
            }),
            news_type_data: [],
        }
    },

    mounted() {
        this.getAboutNewsTypeGrid();
        this.getNewsType();
    },

    methods: {
        async getAboutNewsTypeGrid() {
            try {
                const response = await axios.get('about_news_type/getAboutNewsType');
                this.item = response.data;
                this.loading = false;
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

        selectChange(item){
            const filteredNews = this.item.filter(news => news.news_type_id === item);

            // Find the maximum 'order' value in the filtered data
            const maxOrder = filteredNews.length > 0
                ? Math.max(...filteredNews.map(news => news.order))
                : 0;

            this.form.order = maxOrder + 1;
        },

        create() {
            this.errorMessage = {};
            if (this.form.about_news_name_en === "") {
                this.errorMessage.about_news_name_en = "Name EN is required";
            }
            if (this.form.about_news_name_km === "") {
                this.errorMessage.about_news_name_km = "Name KM is required";
            }
            if (this.form.news_type_id === "" || this.form.news_type_id === null) {
                this.errorMessage.news_type_id = "News Type is required";
            }

            const isDuplicate = this.item.some(item => {
                if(item.news_type_id == this.form.news_type_id){
                    if(item.about_news_name_en.toLowerCase() === this.form.about_news_name_en.toLowerCase()){
                        this.errorMessage.about_news_name_en = "Name EN is duplicate";
                        return true;
                    }
                    if(item.about_news_name_km.toLowerCase() === this.form.about_news_name_km.toLowerCase()){
                        this.errorMessage.about_news_name_km = "Name EN is duplicate";
                        return true;
                    }
                }
                return false;
            });

            if (Object.keys(this.errorMessage).length > 0 || isDuplicate) {
                return;
            }

            this.form.post('about_news_type/create', {
                onSuccess: () => {
                    this.getAboutNewsTypeGrid();
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
            if (this.form.about_news_name_en === "") {
                this.errorMessage.about_news_name_en = "Name EN is required";
            }
            if (this.form.about_news_name_km === "") {
                this.errorMessage.about_news_name_km = "Name KM is required";
            }
            if (this.form.news_type_id === "" || this.form.news_type_id === null) {
                this.errorMessage.news_type_id = "News Type is required";
            }

            const isDuplicate = this.item.some(item => {
                if(item.id != this.form.id) {
                    if(item.news_type_id == this.form.news_type_id){
                        if(item.about_news_name_en.toLowerCase() === this.form.about_news_name_en.toLowerCase()){
                            this.errorMessage.about_news_name_en = "Name EN is duplicate";
                            return true;
                        }
                        if(item.about_news_name_km.toLowerCase() === this.form.about_news_name_km.toLowerCase()){
                            this.errorMessage.about_news_name_km = "Name EN is duplicate";
                            return true;
                        }
                        if(item.order === this.form.order){
                            this.errorMessage.order = "Order Number is duplicate";
                            return true;
                        }
                    }
                }
                return false;
            });

            if (Object.keys(this.errorMessage).length > 0 || isDuplicate) {
                return;
            }

            this.form.post('about_news_type/update', {
                onSuccess: () => {
                    this.getAboutNewsTypeGrid();
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
                this.form.post('about_news_type/delete', {
                    onSuccess: () => {
                        this.getAboutNewsTypeGrid();
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
            this.form.about_news_name_en = data.about_news_name_en;
            this.form.about_news_name_km = data.about_news_name_km;
            this.form.news_type_id = data.news_type_id;
            this.form.order = data.order;
            this.form.isUsed = data.isUsed == 1 ? true : false;
        }
    }
}
</script>
