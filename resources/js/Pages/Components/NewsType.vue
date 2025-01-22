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
                            <v-col cols="12">
                                <v-text-field variant="outlined" density="compact" :label="$t('global.name_en')+'*'" v-model="form.name_en" :error-messages="errorMessage.name_en"></v-text-field>
                                <v-text-field class="mt-2 mb-2" variant="outlined" density="compact" :label="$t('global.name_km')+'*'" v-model="form.name_km" :error-messages="errorMessage.name_km"></v-text-field>
                                <v-text-field variant="outlined" type="number" density="compact" :label="$t('global.order')+'*'" v-model="form.order" :disabled="editMode == true ? false : true" :error-messages="errorMessage.order"></v-text-field>
                                <v-checkbox hide-details :label="$t('global.used')" v-model="form.isUsed" color="primary"></v-checkbox>
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
            loading: true,
            totalItems: 0,
            form: useForm({
                id: '',
                name_en: '',
                name_km: '',
                order: '',
                isUsed: false,
            })
        }
    },

    computed: {
        headers(){
            return[
                { title: this.$t('global.name_en'), align: 'start', key: 'name_en' },
                { title: this.$t('global.name_km'), key: 'name_km', align: 'start' },
                { title: this.$t('global.order'), key: 'order', align: 'start' },
                { title: this.$t('global.used'), key: 'isUsed', align: 'start' },
                { title: this.$t('global.action'), key: 'action', align: 'center' },
            ]
        }
    },

    watch: {
        dialog(newVal) {
            if(newVal == true) {
                if(this.editMode == false){
                    const maxOrder = this.item.reduce((max, currentItem) => {
                        return currentItem.order > max ? currentItem.order : max;
                    }, 0);

                    // Set the form's order to maxOrder + 1
                    this.form.order = maxOrder + 1;
                }
            }
        }
    },

    mounted() {
        this.getNewsTypeGrid();
    },

    methods: {
        async getNewsTypeGrid() {
            try {
                const response = await axios.get('news_type/getNewsTypeGrid');
                this.item = response.data;
                this.loading = false;
            } catch (error) {
                console.error('Error fetching data', error);
            }
        },

        create() {
            this.errorMessage = {};
            if (this.form.name_en === "") {
                this.errorMessage.name_en = "Name EN is required";
            }
            if (this.form.name_km === "") {
                this.errorMessage.name_km = "Name KM is required";
            }

            const isDuplicate = this.item.some(item => {
                if(item.name_en.toLowerCase() === this.form.name_en.toLowerCase()){
                    this.errorMessage.name_en = "Name EN is duplicate";
                    return true;
                }
                if(item.name_km.toLowerCase() === this.form.name_km.toLowerCase()){
                    this.errorMessage.name_km = "Name KM is duplicate";
                    return true;
                }
                if(item.order === this.form.order){
                    this.errorMessage.order = "Order is duplicate";
                    return true;
                }
                return false;
            });

            if (Object.keys(this.errorMessage).length > 0 || isDuplicate) {
                return;
            }

            this.form.post('news_type/create', {
                onSuccess: () => {
                    this.getNewsTypeGrid();
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
            if (this.form.name_en === "") {
                this.errorMessage.name_en = "Name EN is required";
            }
            if (this.form.name_km === "") {
                this.errorMessage.name_km = "Name KM is required";
            }

            const isDuplicate = this.item.some(item => {
                if(item.id != this.form.id) {
                    if(item.name_en.toLowerCase() === this.form.name_en.toLowerCase()){
                        this.errorMessage.name_en = "Name EN is duplicate";
                        return true;
                    }
                    if(item.name_km.toLowerCase() === this.form.name_km.toLowerCase()){
                        this.errorMessage.name_km = "Name EN is duplicate";
                        return true;
                    }
                    if(item.order === parseInt(this.form.order)){
                        this.errorMessage.order = "Order is duplicate";
                        return true;
                    }
                }
                return false;
            });

            if (Object.keys(this.errorMessage).length > 0 || isDuplicate) {
                return;
            }

            this.form.post('news_type/update', {
                onSuccess: () => {
                    this.getNewsTypeGrid();
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
            this.form.name_en = item.name_en;
            this.form.name_km = item.name_km;
            this.form.isUsed = item.isUsed;
        },

        confirmDelete(del) {
            if(del === true){
                this.form.post('news_type/delete', {
                    onSuccess: () => {
                        this.getNewsTypeGrid();
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
            this.form.name_en = data.name_en;
            this.form.name_km = data.name_km;
            this.form.order = data.order;
            this.form.isUsed = data.isUsed == 1 ? true : false;
        }
    }
}
</script>
