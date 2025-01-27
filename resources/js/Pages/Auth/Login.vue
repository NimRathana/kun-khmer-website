<script setup>
import { ref, watch, onMounted } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import TreeImg1 from '@/images/pages/auth-v1-tree.png';
import TreeImg from '@/images/pages/tree.png';
import { Store } from '@/store/index';
import $ from 'jquery';

const colorStore = Store();

defineProps({
    canResetPassword: Boolean,
    status: String,
});

watch(()=>colorStore.theme,(newTheme)=>{
    if(newTheme == 'light'){
        $(".shape1").css({"background-color":"blanchedalmond"});
        $(".shape2").css({"background-color":"blanchedalmond"});
        $(".shape3").css({"background-color":"blanchedalmond"});
    }else{
        $(".shape1").css({"background-color":"rgba(255, 255, 255, 0.1)"});
    }
});
onMounted(() => {
    if(colorStore.theme == 'light'){
        $(".shape1").css({"background":"blanchedalmond"});
        $(".shape2").css({"background-color":"blanchedalmond"});
        $(".shape3").css({"background-color":"blanchedalmond"});
    }else{
        $(".shape1").css({"background-color":"rgba(255, 255, 255, 0.1)"});
    }
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});
const loading = ref(false);

const submit = () => {
    loading.value = true;
    form.transform(data => ({
        ...data,
        remember: form.remember ? 'on' : '',
    })).post(route('login'), {
        onFinish: () => form.reset('password'),
        onSuccess: () => {
            loading.value = false;
            //move button setting
            const button = document.querySelector('.setting_btn');
            let isDragging = false;
            let offsetX, offsetY;

            if(button != undefined){
                button.addEventListener('mousedown', (event) => {
                    isDragging = true;
                    // Calculate the initial offset of the mouse cursor from the button's position
                    offsetX = event.clientX - button.getBoundingClientRect().left;
                    offsetY = event.clientY - button.getBoundingClientRect().top;

                    // Add a style to prevent the text selection during dragging
                    document.body.style.userSelect = 'none';
                });

                document.addEventListener('mousemove', (event) => {
                    if (isDragging) {
                        const x = event.clientX - offsetX;
                        const y = event.clientY - offsetY;

                        // Update the button's position based on the mouse movement
                        button.style.left = `${x}px`;
                        button.style.top = `${y}px`;
                    }
                });

                document.addEventListener('mouseup', () => {
                    isDragging = false;
                    document.body.style.userSelect = ''; // Restore the text selection behavior
                });
            }
        },
        onError: () => {
            loading.value = false;
        },
    });
};
</script>

<template>
    <Head title="Log in" />
    <v-theme-provider>
        <v-responsive class="border rounded">
            <v-app :theme="colorStore.theme">
                <v-main>
                    <div style="position: relative;">
                        <div class="lamp">
                            <div class="holder"></div>
                            <div class="bulb"></div>
                            <div class="light"></div>
                        </div>
                        <div class="lamp2">
                            <div class="holder"></div>
                            <div class="bulb"></div>
                            <div class="light"></div>
                        </div>
                    </div>
                    <div class="shape1"></div>
                    <div class="shape2"></div>
                    <div class="shape3"></div>
                    <div class="ocean">
                        <div class="wave"></div>
                        <div class="wave"></div>
                        <div class="wave"></div>
                    </div>
                    <AuthenticationCard>
                        <template #logo>
                            <AuthenticationCardLogo />
                        </template>

                        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                            {{ status }}
                        </div>

                        <form @submit.prevent="submit">
                            <div>
                                <InputLabel for="email" :value="$t('global.email')" />
                                <TextInput
                                    id="email"
                                    v-model="form.email"
                                    type="email"
                                    class="mt-1 block w-full"
                                    required
                                    autofocus
                                    autocomplete="username"
                                />
                                <InputError class="mt-2" :message="form.errors.email" />
                            </div>

                            <div class="mt-4">
                                <InputLabel for="password" :value="$t('global.password')" />
                                <TextInput
                                    id="password"
                                    v-model="form.password"
                                    type="password"
                                    class="mt-1 block w-full"
                                    required
                                    autocomplete="current-password"
                                />
                                <InputError class="mt-2" :message="form.errors.password" />
                            </div>

                            <div class="mt-4 d-flex justify-between align-center">
                                <div class="d-flex align-center">
                                    <Checkbox v-model:checked="form.remember" name="remember" />
                                    <span class="ms-2 text-sm">{{ $t('login.remember') }}</span>
                                </div>
                                <div>
                                    <Link v-if="canResetPassword" :href="route('password.request')" class="text-sm text-blue-600 hover:text-blue-400 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        <span class="ms-2 text-sm">{{ $t('login.forgot_password') }}?</span>
                                    </Link>
                                </div>
                            </div>

                            <div class="flex items-center justify-center mt-4">
                                <PrimaryButton :loading="loading" class="bg-[#2563eb] w-100 d-flex justify-center" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                    {{ $t('global.login') }}
                                </PrimaryButton>
                            </div>
                        </form>
                        <div class="mt-4 d-flex justify-center text-sm border-hidden">
                            <span>{{ $t('login.do_not_have_an_account') }}?</span>
                            <Link :href="route('register')" class="text-sm text-blue-600 hover:text-blue-400 border-hidden">
                                <span class="ms-2 text-sm">{{ $t('login.create_an_account') }}</span>
                            </Link>
                        </div>
                        <v-row class="mt-4 d-flex justify-center align-center">
                            <v-col cols="5" class="pa-0 ma-0">
                                <v-divider :thickness="1" :class="['my-2', { 'border-opacity-50': colorStore.theme === 'dark', 'border-opacity-100': colorStore.theme !== 'dark' }]"></v-divider>
                            </v-col>
                            <v-col cols="1" class="pa-0 ma-0 d-flex justify-center align-center">{{ $t('global.or') }}</v-col>
                            <v-col cols="5" class="pa-0 ma-0">
                                <v-divider :thickness="1" :class="['my-2', { 'border-opacity-50': colorStore.theme === 'dark', 'border-opacity-100': colorStore.theme !== 'dark' }]"></v-divider>
                            </v-col>
                        </v-row>
                        <v-container class="mt-4">
                            <v-row align="center" justify="center">
                                <v-col cols="auto">
                                    <v-btn density="compact" icon><v-icon size="20" color="primary">mdi-facebook</v-icon></v-btn>
                                </v-col>

                                <v-col cols="auto">
                                    <v-btn density="compact" icon><v-icon size="20" color="info">mdi-twitter</v-icon></v-btn>
                                </v-col>

                                <v-col cols="auto">
                                    <v-btn density="compact" icon><v-icon size="20">mdi-github</v-icon></v-btn>
                                </v-col>

                                <v-col cols="auto">
                                    <v-btn density="compact" icon><v-icon size="20" color="red">mdi-google</v-icon></v-btn>
                                </v-col>
                            </v-row>
                        </v-container>
                    </AuthenticationCard>
                    <img :src="TreeImg1" alt="" class="fixed-image-left animate-float">
                    <img :src="TreeImg" alt="" class="fixed-image-right animate-float">
                </v-main>
            </v-app>
        </v-responsive>
    </v-theme-provider>

</template>
<style scoped>
.v-theme--dark {
    --v-theme-background: 38, 50, 56;
}
.v-theme--light {
    --v-theme-background: 240, 245, 249;
}

.shape1 {
  width: 200px;
  height: 200px;
  top: -50px;
  left: -50px;
}

.shape2 {
  width: 150px;
  height: 150px;
  top: -50px;
  right: -50px;
}

.shape3 {
  width: 100px;
  height: 100px;
  bottom: -50px;
  left: -20px;
}
@keyframes float {
  from {
    transform: translateY(0px);
  }
  to {
    transform: translateY(-20px);
  }
}
.shape1,
.shape2,
.shape3 {
  position: absolute;
  background: rgba(255, 255, 255, 0.1);
  border-radius: 50%;
  animation: float 4s infinite ease-in-out;
}
.fixed-image-left,
.fixed-image-right {
    position: fixed;
    bottom: 20px;
}

.fixed-image-left {
    left:10%;
}

.fixed-image-right {
    right: 10%;
}

@media (max-width: 1500px) {
    .fixed-image-left,
    .fixed-image-right,.lamp,.lamp2 {
        display: none;
    }
}
.ocean {
  height: 5%;
  width:100%;
  position:absolute;
  bottom:0;
  left:0;
  background: #015871;
}

.wave {
  background: url(https://s3-us-west-2.amazonaws.com/s.cdpn.io/85486/wave.svg) repeat-x;
  position: absolute;
  top: -198px;
  width: 9400px;
  height: 198px;
  animation: wave 7s cubic-bezier( 0.36, 0.45, 0.63, 0.53) infinite;
  transform: translate3d(0, 0, 0);
}

.wave:nth-of-type(2) {
  top: -175px;
  animation: wave 7s cubic-bezier( 0.36, 0.45, 0.63, 0.53) -.125s infinite, swell 7s ease -1.25s infinite;
  opacity: 1;
}

@keyframes wave {
  0% {
    margin-left: 0;
  }
  100% {
    margin-left: -1600px;
  }
}

@keyframes swell {
  0%, 100% {
    transform: translate3d(0,-25px,0);
  }
  50% {
    transform: translate3d(0,5px,0);
  }
}
.lamp {
    position: fixed;
    top: 50%;
    left: 30%;
    transform: translate(-50%, -50%);
    width: 10px;
    height: 150px;
    background-color: #444;
}

.lamp2 {
    position: fixed;
    top: 50%;
    left: 70%;
    transform: translate(-50%, -50%);
    width: 10px;
    height: 150px;
    background-color: #444;
}

.lamp::before {
    content: "";
    position: absolute;
    bottom: -20px;
    left: -20px;
    width: 50px;
    height: 20px;
    background-color: #333;
    border-radius: 10px;
}

.lamp2::before {
    content: "";
    position: absolute;
    bottom: -20px;
    left: -20px;
    width: 50px;
    height: 20px;
    background-color: #333;
    border-radius: 10px;
}

.holder {
    position: absolute;
    top: -15px;
    left: -10px;
    width: 30px;
    height: 30px;
    background-color: #333;
    border-radius: 50%;
}

.bulb {
    position: absolute;
    top: -50px;
    left: -25px;
    width: 60px;
    height: 60px;
    background-color: #ffffcc;
    border-radius: 50%;
    box-shadow: 0 0 20px rgba(255, 255, 204, 0.5);
    animation: glow 1.5s infinite alternate;
}

.light {
    position: absolute;
    top: -120px;
    left: -60px;
    width: 120px;
    height: 120px;
    background: radial-gradient(circle, rgba(255, 255, 204, 0.3), rgba(255, 255, 204, 0));
    border-radius: 50%;
    animation: light-glow 1.5s infinite alternate;
}
@keyframes glow {
from {
    box-shadow: 0 0 20px rgba(255, 255, 204, 0.5);
}
to {
    box-shadow: 0 0 50px rgba(255, 255, 204, 1);
}
}

@keyframes light-glow {
from {
    transform: scale(1);
    opacity: 0.3;
}
to {
    transform: scale(1.2);
    opacity: 0.5;
}
}

.animate-float {
    animation: float 4s ease-in-out infinite;
}

@keyframes float {
    0% {
        transform: translateY(0px);
    }
    50% {
        transform: translateY(-10px);
    }
    100% {
        transform: translateY(0px);
    }
}
</style>
