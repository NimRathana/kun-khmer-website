<template>
    <MainApp>
        <v-file-input ref="fileInput" @change="FileChange"></v-file-input>
        <v-btn @click="uploadVideo">Upload Video</v-btn>

        <div v-for="playlist in playlists" :key="playlist.id" class="playlist-card">
        <h3>{{ playlist.title }}</h3>
        <p>{{ playlist.description }}</p>
      </div>
    </MainApp>

    <v-dialog v-model="Errordialog" width="400" persistent>
        <v-card>
            <v-card-item class="d-flex justify-center">
                <v-icon size="50" color="red">mdi-alert-circle</v-icon>
            </v-card-item>
            <v-divider class="border-opacity-100"></v-divider>
            <v-card-text class="text-center">{{ errorMessages }}</v-card-text>
            <v-divider class="border-opacity-100"></v-divider>
            <v-card-actions class="justify-center">
                <v-btn size="small"
                    :text="$t('global.close')"
                    variant="flat"
                    flat
                    color="red"
                    @click="Errordialog = false"
                ></v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>

    <v-snackbar
        v-model="snackbar"
        :color="colorStore.color"
        vertical
        >
        <v-card class="bg-transparent" variant="text">
            <v-card-text>{{ errorMessages }}</v-card-text>
        </v-card>

        <template v-slot:actions>
            <v-btn
            color="red"
            elevation="5"
            @click="snackbar = false"
            >
            {{ $t('global.close') }}
            </v-btn>
        </template>
    </v-snackbar>
</template>

  <script>
  import { ref } from 'vue';
  import axios from 'axios';
  import MainApp from '../MainApp.vue';
  import { useForm } from '@inertiajs/vue3';
  import { useLoadingState } from '@/store/loadingState';
  import { Store } from '@/store/index';

  export default {
    components: { MainApp },
    data() {
        return {
            colorStore: Store(),
            snackbar: false,
            Errordialog: false,
            errorMessages: [],
            form: useForm({
                video: null,
                title: "Default Title",
                description: "Default Description",
                code: ''
            }),
            playlists: [],
        };
    },

    mounted() {
        const urlParams = new URLSearchParams(window.location.search);
        this.form.code = urlParams.get('code');
        this.fetchPlaylists();
    },

    methods: {
        async fetchPlaylists() {
            try {
                await axios.get("/youtube/playlists")
                .then((res)=>{
                    this.playlists = res.data;
                }).catch((err)=>{
                    window.location.href = err.response.data.errors;
                });
            } catch (err) {
                console.log(err)
            }
        },

        uploadVideo(){
            this.form.post('youtube', {
                onProgress: (progress) => {
                    useLoadingState().startLoading();
                },
                onSuccess: (res) => {
                    if (res.props.authUrl){
                        window.location.href = res.props.authUrl;
                    }

                    if(res.props.message == 'Please select a video file to upload.'){
                        this.Errordialog = true;
                        this.errorMessages = res.props.message;
                    }

                    if(res.props.success == 'Video uploaded successfully.'){
                        this.snackbar = true;
                        this.errorMessages = res.props.success;
                    }
                    useLoadingState().stopLoading();
                },
                onError: (errors) => {
                    this.errorMessages = errors[0];
                    this.Errordialog = true;
                    useLoadingState().stopLoading();
                },
            });
        },

        FileChange(event) {
            const file = event.target.files[0];

            if (file) {
                this.form.video = file;
            } else {
                console.error("No file selected or invalid file.");
            }
        }
    },
  };
  </script>

  <style scoped>
  /* Add your styles here */
  </style>
