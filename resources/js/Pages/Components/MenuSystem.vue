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
                    :items="MenuItem"
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
                    <template #item.name="{ item }">
                        <span>{{ item.name }}</span>
                    </template>
                    <template #item.icon="{ item }">
                        <v-icon>{{ item.icon }}</v-icon>
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
                :title="editMode?'Update':'Create'"
            >
                <v-divider></v-divider>
                <form @submit.prevent="editMode ? updateMenu() : createMenu()" enctype="multipart/form-data">
                    <v-card-text>
                        <v-row dense>
                            <v-col cols="12" sm="6">
                                <v-text-field variant="outlined" density="compact" label="Name" v-model="form.name" :error-messages="errorMessage.name"></v-text-field>
                            </v-col>
                            <v-col cols="12" sm="6">
                                <v-text-field variant="outlined" density="compact" label="Url" v-model="form.url" :error-messages="errorMessage.url"></v-text-field>
                            </v-col>
                            <v-col cols="12" sm="6">
                                <v-text-field variant="outlined" density="compact" label="Order" v-model="form.order" type="number" :error-messages="errorMessage.order"></v-text-field>
                            </v-col>
                            <v-col cols="12" sm="6">
                                <v-text-field variant="outlined" density="compact" label="Icon" v-model="form.icon" :error-messages="errorMessage.icon"></v-text-field>
                            </v-col>
                        </v-row>
                    </v-card-text>
                    <v-divider></v-divider>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn
                            text="Close"
                            color="red"
                            @click="dialog = false, errorMessage = '', form.reset(), editMode = false"
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

<script setup>
import { ref, onMounted, watch } from 'vue';
import MainApp from '../MainApp.vue';
import axios from 'axios';
import { useForm } from '@inertiajs/vue3';
import { Store } from '@/store/index';

const colorStore = Store();
const MenuItem = ref([]);
const deleteDialog = ref(false);
const editMode = ref(false);
const errorMessage = ref('');
const search = ref(null);
const dialog = ref(false);
const itemsPerPage = ref(5);
const headers = ref([
  { title: 'Name', align: 'start', key: 'name' },
  { title: 'Url', key: 'url', align: 'start' },
  { title: 'Order', key: 'order', align: 'start' },
  { title: 'Icon', key: 'icon', align: 'start' },
  { title: 'Actions', key: 'action', align: 'center' },
]);
const loading = ref(true);
const totalItems = ref(0);
const form = useForm({
    id: '',
    name: '',
    url: '',
    order: '',
    icon: ''
});

onMounted(()=>{
    getMenuGrid();
});

watch(
  () => colorStore.menuItem,
  (newItem) => {
    MenuItem.value = newItem;
  }
);

window.onresize = function() {
};

const getMenuGrid = async () => {
    try {
        const response = await axios.get('menu-system/getMenuGrid');
        MenuItem.value = response.data;
        colorStore.menuItem = response.data;
        loading.value = false;
    } catch (error) {
        console.error('Error fetching data', error);
    }
};

const createMenu = () => {
    errorMessage.value = {};
    if (form.name === "") {
        errorMessage.value.name = "Name is required";
    }
    if (form.url === "") {
        errorMessage.value.url = "URL is required";
    }
    if (form.order === "") {
        errorMessage.value.order = "Order is required";
    }
    if (form.icon === "") {
        errorMessage.value.icon = "Icon is required";
    }

    const isDuplicate = MenuItem.value.some(item => {
        if(item.name.toLowerCase() === form.name.toLowerCase()){
            errorMessage.value.name = "Name is duplicate";
            return true;
        }
        if(item.url.toLowerCase() === form.url.toLowerCase()){
            errorMessage.value.url = "URL is duplicate";
            return true;
        }
        if(item.order.toString() === form.order){
            errorMessage.value.order = "Order is duplicate";
            return true;
        }
        return false;
    });

    if (Object.keys(errorMessage.value).length > 0 || isDuplicate) {
        return;
    }

    form.post('menu-system/createMenu', {
        onSuccess: () => {
            getMenuGrid();
            form.reset();
            dialog.value = false;
            errorMessage.value = "";

        },
        onError: (errors) => {
            // errorMessage.value = errors;
        },
    });
};

const updateMenu = () => {
    errorMessage.value = {};

    if (form.name === "") {
        errorMessage.value.name = "Name is required";
    }
    if (form.url === "") {
        errorMessage.value.url = "URL is required";
    }
    if (form.order === "") {
        errorMessage.value.order = "Order is required";
    }
    if (form.icon === "") {
        errorMessage.value.icon = "Icon is required";
    }

    const isDuplicate = MenuItem.value.some(item => {
        if(item.id != form.id){
            if(item.name.toLowerCase() === form.name.toLowerCase()){
                errorMessage.value.name = "Name is duplicate";
                return true;
            }
            if(item.url.toLowerCase() === form.url.toLowerCase()){
                errorMessage.value.url = "URL is duplicate";
                return true;
            }
            if(item.order.toString() === form.order){
                errorMessage.value.order = "Order is duplicate";
                return true;
            }
            return false;
        }
    });

    if (Object.keys(errorMessage.value).length > 0 || isDuplicate) {
        return;
    }

    form.post('menu-system/updateMenu', {
        onSuccess: () => {
            getMenuGrid();
            form.reset();
            dialog.value = false;
            errorMessage.value = "";
        },
        onError: (errors) => {
            // errorMessage.value = errors;
        },
    });
};

function deleteMenu (item) {
    deleteDialog.value = true;
    form.id = item.id;
    form.name = item.name;
    form.url = item.url;
    form.order = item.order;
    form.icon = item.icon;
};

function confirmDelete (del){
    if(del == true){
        form.post('menu-system/deleteMenu', {
            onSuccess: () => {
                getMenuGrid();
                form.reset();
                deleteDialog.value = false;
            },
            onError: (errors) => {
                // errorMessage.value = errors;
            },
        });
    }
};

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
    form.name = data.name;
    form.url = data.url;
    form.order = data.order;
    form.icon = data.icon;
};
</script>
