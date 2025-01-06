<template>
    <MainApp>
        <v-row class="head">
            <v-col cols="12" md="6">
                <v-text-field variant="outlined" density="compact" label="search" v-model="search"></v-text-field>
            </v-col>
            <v-col cols="12" md="6">
                <v-btn color="primary" style="float: right;" @click="dialog = true">
                    <v-icon>mdi-plus</v-icon>Add
                </v-btn>
            </v-col>
        </v-row>
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
                <v-icon class="ml-2" color="red">mdi-delete</v-icon>
            </template>
        </v-data-table>

        <v-dialog v-model="dialog" max-width="600">
            <v-card
                prepend-icon="mdi-account"
                title="User Profile"
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
                            @click="dialog = false, errorMessage = '', form.reset()"
                        ></v-btn>

                        <v-btn
                            color="primary"
                            text="Save"
                            type="submit"
                            @click="createMenu()"
                        ></v-btn>
                    </v-card-actions>
                </form>
            </v-card>
        </v-dialog>
    </MainApp>
</template>

<script setup>
import { ref, onMounted, getCurrentInstance } from 'vue';
import $ from 'jquery';
import MainApp from '../MainApp.vue';
import axios from 'axios';
import { useForm } from '@inertiajs/vue3';

const MenuItem = ref([]);
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
    name: '',
    url: '',
    order: '',
    icon: ''
});

onMounted(()=>{
    debugger
    const token = localStorage.getItem('token');
    getMenuGrid();
    const instance = getCurrentInstance();
    const helper = instance?.proxy.$helper;
    helper.GetGridHeight();
});
window.onresize = function() {
};
const getMenuGrid = async () => {
    try {
        const response = await axios.get('api/getMenuGrid');
        MenuItem.value = response.data;
        loading.value = false;
    } catch (error) {
        console.error('Error fetching data', error);
    }
};
const createMenu = async () => {
    axios.post('/api/createMenu', form);
    // form.post('/api/createMenu', {
    //     onFinish: () => {
    //     },
    //     onError: (errors) => {
    //         errorMessage.value = errors;
    //     }
    // });
};

const updateMenu = () => {
    debugger
}
function editItem (event, item) {
    var data = null;
    if(event.type == "dblclick"){
        data = item.item;
    }else{
        data = item;
    }
};
</script>
