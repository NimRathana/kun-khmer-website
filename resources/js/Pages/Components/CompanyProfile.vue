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
                    <template v-slot:item.logo="{ item }">
                        <v-img
                            :src="getImageUrl(item.logo)"
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

        <v-dialog v-model="dialog" max-width="1000" persistent>
            <v-card
                :prepend-icon="editMode?'mdi-pen':'mdi-plus'"
                :title="editMode ? $t('global.update') : $t('global.create')"
            >
                <v-divider></v-divider>

                <form @submit.prevent="editMode ? update() : create()" enctype="multipart/form-data">
                    <v-card-text>
                        <v-row dense>
                            <v-col cols="12" md="8">
                                <v-row>
                                    <v-col cols="12" sm="6">
                                        <v-text-field variant="outlined" density="compact" :label="$t('global.name_en')+'*'" v-model="form.name_en" :error-messages="errorMessage.name_en"></v-text-field>
                                    </v-col>
                                    <v-col cols="12" sm="6">
                                        <v-text-field variant="outlined" density="compact" :label="$t('global.name_km')+'*'" v-model="form.name_km" :error-messages="errorMessage.name_km"></v-text-field>
                                    </v-col>
                                    <v-col cols="12" sm="6">
                                        <v-text-field variant="outlined" type="date" density="compact" :label="$t('company_profile.created_date')" v-model="form.created_date"></v-text-field>
                                    </v-col>
                                    <v-col cols="12" sm="6">
                                        <v-text-field variant="outlined" type="email" density="compact" :label="$t('global.email')" v-model="form.email"></v-text-field>
                                    </v-col>
                                    <v-col cols="12" sm="6">
                                        <v-text-field variant="outlined" density="compact" :label="$t('company_profile.phone')" v-model="form.phone"></v-text-field>
                                    </v-col>
                                    <v-col cols="12" sm="6">
                                        <v-checkbox hide-details :label="$t('global.used')" v-model="form.isUsed" color="primary"></v-checkbox>
                                    </v-col>
                                    <v-col cols="12" sm="6">
                                        <v-textarea variant="outlined" density="compact" :label="$t('global.remark')" v-model="form.remark"></v-textarea>
                                    </v-col>
                                    <v-col cols="12" sm="6">
                                        <v-textarea variant="outlined" density="compact" :label="$t('company_profile.location')" v-model="form.location"></v-textarea>
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
                                                :alt="$t('global.no_image')"
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

<script setup>
import { ref, onMounted, computed } from 'vue';
import MainApp from '../MainApp.vue';
import axios from 'axios';
import { useForm } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();
const item = ref([]);
const imageUrl = ref(null);
const fileInput = ref(null);
const deleteDialog = ref(false);
const editMode = ref(false);
const errorMessage = ref('');
const search = ref(null);
const dialog = ref(false);
const itemsPerPage = ref(5);
const headers = computed(()=>
    [
        { title: t('global.name_km'), align: 'start', key: 'name_km' },
        { title: t('global.name_en'), key: 'name_en', align: 'start' },
        { title: t('company_profile.logo'), key: 'logo', align: 'start' },
        { title: t('company_profile.created_date'), key: 'created_date', align: 'start' },
        { title: t('company_profile.location'), key: 'location', align: 'start' },
        { title: t('company_profile.phone'), key: 'phone', align: 'start' },
        { title: t('global.email'), key: 'email', align: 'start' },
        { title: t('global.used'), key: 'isUsed', align: 'start' },
        { title: t('global.remark'), key: 'remark', align: 'start' },
        { title: t('global.action'), key: 'action', align: 'center' },
    ]
);
const loading = ref(true);
const totalItems = ref(0);
const form = useForm({
    id: '',
    name_km: '',
    name_en: '',
    logo: '',
    created_date: '',
    location: '',
    phone: '',
    email: '',
    isUsed: false,
    remark: '',
    logo_delete: '',
});

onMounted(()=>{
    getCompanyProfileGrid();
});

const getCompanyProfileGrid = async () => {
    try {
        const response = await axios.get('company_profile/getCompanyProfile');
        item.value = response.data;
        loading.value = false;
    } catch (error) {
        console.error('Error fetching data', error);
    }
};

const create = () => {
    errorMessage.value = {};
    if (form.name_en === "") {
        errorMessage.value.name_en = "Name EN is required";
    }
    if (form.name_km === "") {
        errorMessage.value.name_km = "Name KM is required";
    }

    const isDuplicate = item.value.some(item => {
        if(item.name_en.toLowerCase() === form.name_en.toLowerCase()){
            errorMessage.value.name_en = "Name EN is duplicate";
            return true;
        }
        if(item.name_km.toLowerCase() === form.name_km.toLowerCase()){
            errorMessage.value.name_km = "Name KM is duplicate";
            return true;
        }
        return false;
    });

    if (Object.keys(errorMessage.value).length > 0 || isDuplicate) {
        return;
    }

    form.post('company_profile/create', {
        onSuccess: () => {
            getCompanyProfileGrid();
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
    if (form.name_en === "") {
        errorMessage.value.name_en = "Name EN is required";
    }
    if (form.name_km === "") {
        errorMessage.value.name_km = "Name KM is required";
    }

    const isDuplicate = item.value.some(item => {
        if(item.id != form.id){
            if(item.name_en.toLowerCase() === form.name_en.toLowerCase()){
                errorMessage.value.name_en = "Name EN Code is duplicate";
                return true;
            }
            if(item.name_km.toLowerCase() === form.name_km.toLowerCase()){
                errorMessage.value.name_km = "Name KM is duplicate";
                return true;
            }
            return false;
        }
    });

    if (Object.keys(errorMessage.value).length > 0 || isDuplicate) {
        return;
    }

    form.post('company_profile/update', {
        onSuccess: () => {
            getCompanyProfileGrid();
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
    form.logo = item.logo;
}

function confirmDelete (del){
    if(del == true){
        form.post('company_profile/delete', {
            onSuccess: () => {
                getCompanyProfileGrid();
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
    form.name_en = data.name_en;
    form.name_km = data.name_km;
    form.logo = data.logo;
    form.location = data.location;
    form.created_date = data.created_date;
    form.phone = data.phone;
    form.email = data.email;
    form.isUsed = data.isUsed == 1 ? true : false;
    form.remark = data.remark;
    imageUrl.value = getImageUrl(data.logo);
    form.logo_delete = data.logo;
};

function triggerFileInput() {
    fileInput.value?.$el?.querySelector("input[type='file']").click();
};

function FileChange(event) {
    const file = event.target.files[0];

    if (file) {
        form.logo = file;
        const imagePreview = URL.createObjectURL(file);
        imageUrl.value = imagePreview;
    } else {
        imageUrl.value = null;
        console.error("No file selected or invalid file.");
    }
}

function getImageUrl(name) {
    return new URL(`/storage/images/CompanyProfile/${name}`, import.meta.url).href
}
</script>
