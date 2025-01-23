<template>
    <MainApp>
        <v-file-input ref="fileInput" @change="FileChange"></v-file-input>
        <v-btn @click="uploadVideo">Upload Video</v-btn>
    </MainApp>
</template>

  <script>
  import { ref } from 'vue';
  import axios from 'axios';
  import MainApp from '../MainApp.vue';
  import { useForm } from '@inertiajs/vue3';
  import { useLoadingState } from '@/store/loadingState';

  export default {
    components: { MainApp },
    data() {
        return {
            form: useForm({
                video: null,
                title: "Default Title",
                description: "Default Description",
                code: ''
            }),
        };
    },

    mounted() {
        const urlParams = new URLSearchParams(window.location.search);
        this.form.code = urlParams.get('code');
    },

    methods: {
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
                        alert(res.props.message);
                    }

                    if(res.props.success == 'Video uploaded successfully.'){
                        alert(res.props.message);
                    }
                    useLoadingState().stopLoading();
                },
                onError: (errors) => {
                    console.log(errors);
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
