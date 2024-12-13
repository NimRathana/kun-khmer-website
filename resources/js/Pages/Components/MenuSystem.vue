<template>
    <MainApp>
        <v-row>
            <!-- First column (Text Field and Select) -->
            <v-col cols="12" md="6">
            <v-text-field variant="outlined" label="demo"></v-text-field>
            <v-autocomplete
                label="Select"
                variant="outlined"
                :items="['California', 'Colorado', 'Florida', 'Georgia', 'Texas', 'Wyoming']"
            ></v-autocomplete>
            </v-col>

            <!-- Second column (Text Field) -->
            <v-col cols="12" md="6">
                <v-text-field variant="outlined" label="demo"></v-text-field>
                <v-select
                label="Select"
                variant="outlined"
                :items="['California', 'Colorado', 'Florida', 'Georgia', 'Texas', 'Wyoming']"
                ></v-select>
            </v-col>
        </v-row>
        <v-data-table
            :headers="headers"
            :items="desserts"
            :items-per-page="itemsPerPage"
            :loading="loading"
            :total-items="totalItems"
            @dblclick:row="editItem"
            :server-items-length="totalItems"
        >
            <template #item.name="{ item }">
            <span>{{ item.name }}</span>
            </template>
      </v-data-table>
    </MainApp>
</template>

<script setup>
import { ref } from 'vue';
import MainApp from '../MainApp.vue';

const desserts = ref([
  { name: 'Frozen Yogurt', calories: 159, fat: 6.0, carbs: 24, protein: 4.0, iron: '1' },
  { name: 'Jelly bean', calories: 375, fat: 0.0, carbs: 94, protein: 0.0, iron: '0' },
  { name: 'KitKat', calories: 518, fat: 26.0, carbs: 65, protein: 7, iron: '6' },
  { name: 'Eclair', calories: 262, fat: 16.0, carbs: 23, protein: 6.0, iron: '7' },
  { name: 'Gingerbread', calories: 356, fat: 16.0, carbs: 49, protein: 3.9, iron: '16' },
  { name: 'Ice cream sandwich', calories: 237, fat: 9.0, carbs: 37, protein: 4.3, iron: '1' },
  { name: 'Lollipop', calories: 392, fat: 0.2, carbs: 98, protein: 0, iron: '2' },
  { name: 'Cupcake', calories: 305, fat: 3.7, carbs: 67, protein: 4.3, iron: '8' },
  { name: 'Honeycomb', calories: 408, fat: 3.2, carbs: 87, protein: 6.5, iron: '45' },
  { name: 'Donut', calories: 452, fat: 25.0, carbs: 51, protein: 4.9, iron: '22' },
]);

const itemsPerPage = ref(5);
const headers = ref([
  { title: 'Dessert (100g serving)', align: 'start', sortable: false, key: 'name' },
  { title: 'Calories', key: 'calories', align: 'end' },
  { title: 'Fat (g)', key: 'fat', align: 'end' },
  { title: 'Carbs (g)', key: 'carbs', align: 'end' },
  { title: 'Protein (g)', key: 'protein', align: 'end' },
  { title: 'Iron (%)', key: 'iron', align: 'end' },
]);
const loading = ref(true);
const totalItems = ref(0);

function editItem (event, {item}) {
    debugger
};
</script>
<style>

</style>
