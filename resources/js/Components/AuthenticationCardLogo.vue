<script setup>
import { Link } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import { Store } from '@/store/index';
import axios from 'axios';

const colorStore = Store();
const company_profile = ref([]);

onMounted(async ()=>{
    getCompanyProfile();
});

async function getCompanyProfile() {
    try {
        const response = await axios.get('getCompanyProfile');
        if (response.data && Array.isArray(response.data)) {
            // Filter for `isUsed = 1`
            const filteredProfiles = response.data.filter(profile => profile.isUsed === 1);

            // Find the profile with the latest `updated_at`
            if (filteredProfiles.length > 0) {
                company_profile.value = filteredProfiles.reduce((latest, current) => {
                    return new Date(current.updated_at) > new Date(latest.updated_at) ? current : latest;
                });
            } else {
                company_profile.value = null; // No profiles meet the condition
            }

        } else {
            console.error('Unexpected response format:', response.data);
        }
    } catch (error) {
        console.error('Error fetching data', error);
    }
};

function getStorageImageUrl(name) {
    return new URL(`/storage/images/${name}`, import.meta.url).href
};
</script>

<template>
    <Link :href="'/'">
        <v-avatar size="80" :color="colorStore.color">
            <v-img
                v-if="company_profile.logo !== null"
                alt="mdi-account-tie"
                :src="getStorageImageUrl('CompanyProfile/' + company_profile.logo)"
              ></v-img>
              <v-icon
                v-else
                size="50"
                icon="mdi-account-tie"
              ></v-icon>
        </v-avatar>
    </Link>
</template>
