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
                                <v-text-field variant="outlined" density="compact" :label="$t('parameter.parameter_code')+'*'" v-model="form.parameter_code" :error-messages="errorMessage.parameter_code"></v-text-field>
                            </v-col>
                            <v-col cols="12" sm="6">
                                <v-text-field variant="outlined" density="compact" :label="$t('parameter.value')+'*'" v-model="form.value" :error-messages="errorMessage.value"></v-text-field>
                            </v-col>
                            <v-col cols="12" sm="6">
                                <v-text-field variant="outlined" density="compact" :label="$t('parameter.text_en')+'*'" v-model="form.text_en" :error-messages="errorMessage.text_en"></v-text-field>
                            </v-col>
                            <v-col cols="12" sm="6">
                                <v-text-field variant="outlined" density="compact" :label="$t('parameter.text_km')+'*'" v-model="form.text_km" :error-messages="errorMessage.text_km"></v-text-field>
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

<script setup>
import { ref, onMounted, computed } from 'vue';
import MainApp from '../MainApp.vue';
import axios from 'axios';
import { useForm } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();
const item = ref([]);
const deleteDialog = ref(false);
const editMode = ref(false);
const errorMessage = ref('');
const search = ref(null);
const dialog = ref(false);
const itemsPerPage = ref(5);
const headers = computed(()=>
    [
        { title: t('parameter.parameter_code'), align: 'start', key: 'parameter_code' },
        { title: t('parameter.value'), key: 'value', align: 'start' },
        { title: t('parameter.text_km'), key: 'text_km', align: 'start' },
        { title: t('parameter.text_en'), key: 'text_en', align: 'start' },
        { title: t('global.action'), key: 'action', align: 'center' },
    ]
);
const loading = ref(true);
const totalItems = ref(0);
const form = useForm({
    id: '',
    parameter_code: '',
    value: '',
    text_km: '',
    text_en: ''
});

onMounted(()=>{
    getParameterGrid();
});

const getParameterGrid = async () => {
    try {
        const response = await axios.get('parameter/getParameter');
        item.value = response.data;
        loading.value = false;
    } catch (error) {
        console.error('Error fetching data', error);
    }
};

const create = () => {
    errorMessage.value = {};
    if (form.parameter_code === "") {
        errorMessage.value.parameter_code = "Parameter Code is required";
    }
    if (form.value === "") {
        errorMessage.value.value = "Value is required";
    }
    if (form.text_en === "") {
        errorMessage.value.text_en = "Text EN is required";
    }
    if (form.text_km === "") {
        errorMessage.value.text_km = "Text KM is required";
    }

    const isDuplicate = item.value.some(item => {
        if(item.parameter_code.toLowerCase() === form.parameter_code.toLowerCase()){
            errorMessage.value.parameter_code = "Parameter Code is duplicate";
            return true;
        }
        if(item.value.toLowerCase() === form.value.toLowerCase()){
            errorMessage.value.value = "Value is duplicate";
            return true;
        }
        return false;
    });

    if (Object.keys(errorMessage.value).length > 0 || isDuplicate) {
        return;
    }

    form.post('parameter/create', {
        onSuccess: () => {
            getParameterGrid();
            form.reset();
            dialog.value = false;
            errorMessage.value = "";
        },
        onError: (errors) => {
            // errorMessage.value = errors;
        },
    });
};

const update = () => {
    errorMessage.value = {};
    if (form.parameter_code === "") {
        errorMessage.value.parameter_code = "Parameter Code is required";
    }
    if (form.value === "") {
        errorMessage.value.value = "Value is required";
    }
    if (form.text_en === "") {
        errorMessage.value.text_en = "Text EN is required";
    }
    if (form.text_km === "") {
        errorMessage.value.text_km = "Text KM is required";
    }

    const isDuplicate = item.value.some(item => {
        if(item.id != form.id){
            if(item.parameter_code.toLowerCase() === form.parameter_code.toLowerCase()){
                errorMessage.value.parameter_code = "Parameter Code is duplicate";
                return true;
            }
            if(item.value.toLowerCase() === form.value.toLowerCase()){
                errorMessage.value.value = "Value is duplicate";
                return true;
            }
            return false;
        }
    });

    if (Object.keys(errorMessage.value).length > 0 || isDuplicate) {
        return;
    }

    form.post('parameter/update', {
        onSuccess: () => {
            getParameterGrid();
            form.reset();
            dialog.value = false;
            errorMessage.value = "";
        },
        onError: (errors) => {
            // errorMessage.value = errors;
        },
    });
}

function deleteMenu (item) {
    deleteDialog.value = true;
    form.id = item.id;
    form.parameter_code = item.parameter_code;
    form.value = item.value;
    form.text_en = item.text_en;
    form.text_km = item.text_km;
}

function confirmDelete (del){
    if(del == true){
        form.post('parameter/delete', {
            onSuccess: () => {
                getParameterGrid();
                form.reset();
                deleteDialog.value = false;
            },
            onError: (errors) => {
                // errorMessage.value = errors;
            },
        });
    }
}

function editItem (event, item) {
    var data = null;
    editMode.value = true;
    dialog.value = true;
    if(event.type == "dblclick"){
        data = item.item;
    }else{
        data = item;
    }
    form.id = data.id;
    form.parameter_code = data.parameter_code;
    form.value = data.value;
    form.text_en = data.text_en;
    form.text_km = data.text_km;
};
</script>
