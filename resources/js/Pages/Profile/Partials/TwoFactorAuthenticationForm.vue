<script setup>
import { ref, computed, watch } from 'vue';
import { router, useForm, usePage } from '@inertiajs/vue3';
import ActionSection from '@/Components/ActionSection.vue';
import ConfirmsPassword from '@/Components/ConfirmsPassword.vue';
import DangerButton from '@/Components/DangerButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Store } from '@/store/index';
import axios from 'axios';

const colorStore = Store();

const props = defineProps({
    requiresConfirmation: Boolean,
});

const page = usePage();
const enabling = ref(false);
const confirming = ref(false);
const disabling = ref(false);
const qrCode = ref(null);
const setupKey = ref(null);
const recoveryCodes = ref([]);

const confirmationForm = useForm({
    code: '',
});

const twoFactorEnabled = computed(
    () => ! enabling.value && page.props.auth.user?.two_factor_enabled,
);

watch(twoFactorEnabled, () => {
    if (! twoFactorEnabled.value) {
        confirmationForm.reset();
        confirmationForm.clearErrors();
    }
});

const enableTwoFactorAuthentication = () => {
    enabling.value = true;

    router.post(route('two-factor.enable'), {}, {
        preserveScroll: true,
        onSuccess: () => Promise.all([
            showQrCode(),
            showSetupKey(),
            showRecoveryCodes(),
        ]),
        onFinish: () => {
            enabling.value = false;
            confirming.value = props.requiresConfirmation;
        },
    });
};

const showQrCode = () => {
    return axios.get(route('two-factor.qr-code')).then(response => {
        qrCode.value = response.data.svg;
    });
};

const showSetupKey = () => {
    return axios.get(route('two-factor.secret-key')).then(response => {
        setupKey.value = response.data.secretKey;
    });
}

const showRecoveryCodes = () => {
    return axios.get(route('two-factor.recovery-codes')).then(response => {
        recoveryCodes.value = response.data;
    });
};

const confirmTwoFactorAuthentication = () => {
    confirmationForm.post(route('two-factor.confirm'), {
        errorBag: "confirmTwoFactorAuthentication",
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            confirming.value = false;
            qrCode.value = null;
            setupKey.value = null;
        },
    });
};

const regenerateRecoveryCodes = () => {
    axios
        .post(route('two-factor.recovery-codes'))
        .then(() => showRecoveryCodes());
};

const disableTwoFactorAuthentication = () => {
    disabling.value = true;

    router.delete(route('two-factor.disable'), {
        preserveScroll: true,
        onSuccess: () => {
            disabling.value = false;
            confirming.value = false;
        },
    });
};
</script>

<template>
    <ActionSection>
        <template #title>
            {{ $t('profile.two_factor_authentication') }}
        </template>

        <template #description>
            {{ $t('profile.add_additional_security_to_your_account_using_two_factor_authentication') }}
        </template>

        <template #content>
                <VRow>
                    <VCol>
                        <h3 v-if="twoFactorEnabled && ! confirming" class="text-lg font-medium">
                            {{ $t('profile.you_have_enabled_two_factor_authentication') }}
                        </h3>

                        <h3 v-else-if="twoFactorEnabled && confirming" class="text-lg font-medium">
                            {{ $t('profile.finish_enabling_two_factor_authentication') }}
                        </h3>

                        <h3 v-else class="text-lg font-medium">
                            {{ $t('profile.you_have_not_enabled_two_factor_authentication') }}
                        </h3>

                        <VRow class="mt-3 max-w-xl text-sm">
                            <VCol>
                                <p>
                                    {{ $t('profile.when_two_factor_authentication_is_enabled') }}
                                </p>
                            </VCol>
                        </VRow>

                        <div v-if="twoFactorEnabled">
                            <div v-if="qrCode">
                                <VRow class="mt-4 max-w-xl text-sm text-gray-600">
                                    <VCol>
                                        <p v-if="confirming" class="font-semibold">
                                            {{ $t('profile.to_finish_enabling_two_factor_authentication') }}
                                        </p>

                                        <p v-else>
                                            {{ $t('profile.two_factor_authentication_is_now_enabled') }}
                                        </p>
                                    </VCol>
                                </VRow>

                                <div class="mt-4 p-2 inline-block bg-white" v-html="qrCode" />

                                <div v-if="setupKey" class="mt-4 max-w-xl text-sm text-gray-600">
                                    <p class="font-semibold">
                                        {{ $t('profile.setup_key') }}: <span v-html="setupKey"></span>
                                    </p>
                                </div>

                                <div v-if="confirming" class="mt-4">
                                    <InputLabel for="code" value="Code" />

                                    <TextInput
                                        id="code"
                                        v-model="confirmationForm.code"
                                        type="text"
                                        name="code"
                                        class="block mt-1 w-1/2"
                                        inputmode="numeric"
                                        autofocus
                                        autocomplete="one-time-code"
                                        @keyup.enter="confirmTwoFactorAuthentication"
                                    />

                                    <InputError :message="confirmationForm.errors.code" class="mt-2" />
                                </div>
                            </div>

                            <div v-if="recoveryCodes.length > 0 && ! confirming">
                                <VRow class="mt-4 max-w-xl text-sm text-gray-600">
                                    <VCol>
                                        <p class="font-semibold">
                                            {{ $t('profile.store_these_recovery_codes_in_a_secure_password_manager') }}
                                        </p>
                                    </VCol>
                                </VRow>

                                <VRow class="grid gap-1 max-w-xl mt-4 px-4 py-4 font-mono text-sm rounded-lg">
                                    <VCol v-for="code in recoveryCodes" :key="code">
                                        {{ code }}
                                    </VCol>
                                </VRow>
                            </div>
                        </div>

                        <VRow class="mt-5">
                            <VCol v-if="! twoFactorEnabled">
                                <ConfirmsPassword @confirmed="enableTwoFactorAuthentication">
                                    <PrimaryButton type="button" :class="{ 'opacity-25': enabling }" :disabled="enabling" :style="{'background': colorStore.color}">
                                        {{ $t('profile.enable') }}
                                    </PrimaryButton>
                                </ConfirmsPassword>
                            </VCol>

                            <VCol v-else>
                                <ConfirmsPassword @confirmed="confirmTwoFactorAuthentication">
                                    <PrimaryButton
                                        v-if="confirming"
                                        type="button"
                                        class="me-3"
                                        :class="{ 'opacity-25': enabling }"
                                        :disabled="enabling" :style="{'background-color': colorStore.color}"
                                    >
                                    {{ $t('profile.confirm') }}
                                    </PrimaryButton>
                                </ConfirmsPassword>

                                <ConfirmsPassword @confirmed="regenerateRecoveryCodes">
                                    <SecondaryButton
                                        v-if="recoveryCodes.length > 0 && ! confirming"
                                        class="me-3" :style="{'background': colorStore.color}"
                                    >
                                    {{ $t('profile.regenerate_recovery_codes') }}
                                    </SecondaryButton>
                                </ConfirmsPassword>

                                <ConfirmsPassword @confirmed="showRecoveryCodes">
                                    <SecondaryButton
                                        v-if="recoveryCodes.length === 0 && ! confirming"
                                        class="me-3"
                                        :style="{'background': colorStore.color}"
                                    >
                                    {{ $t('profile.show_recovery_codes') }}
                                    </SecondaryButton>
                                </ConfirmsPassword>

                                <ConfirmsPassword @confirmed="disableTwoFactorAuthentication">
                                    <SecondaryButton
                                        v-if="confirming"
                                        :class="{ 'opacity-25': disabling }"
                                        :disabled="disabling"
                                    >
                                    {{ $t('global.close') }}
                                    </SecondaryButton>
                                </ConfirmsPassword>

                                <ConfirmsPassword @confirmed="disableTwoFactorAuthentication">
                                    <DangerButton
                                        v-if="! confirming"
                                        :class="{ 'opacity-25': disabling }"
                                        :disabled="disabling"
                                    >
                                    {{ $t('profile.disable') }}
                                    </DangerButton>
                                </ConfirmsPassword>
                            </VCol>
                        </VRow>
                    </VCol>
                </VRow>
        </template>
    </ActionSection>
</template>
