<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { useForm } from "@inertiajs/inertia-vue3";
import FormSection from "@/Components/FormSection.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import ActionMessage from "@/Components/ActionMessage.vue";

const props = defineProps({
    user: Object,
});

const form = useForm({
    _method: "PUT",
    twitter_api_key: props.user.twitter_api_key,
    instagram_api_key: props.user.instagram_api_key,
    gpt_api_key: props.user.gpt_api_key,
});

const updateSocialKeys = () => {
    form.post(route("social-api-key.update"), {
        errorBag: "updateSocialApiKeysInformation",
        preserveScroll: true,
    });
};
</script>

<template>
    <AppLayout title="Social Api keys">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Social Api Secrets
            </h2>
        </template>

        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <div>
                    <FormSection @submitted="updateSocialKeys">
                        <template #title> Api keys </template>

                        <template #description>
                            Please provide your social api keys here. You can
                            get them from your social media developer account.
                        </template>

                        <template #form>
                            <div class="col-span-6 sm:col-span-4">
                                <InputLabel
                                    for="twitter_api_key"
                                    value="Twitter Api Key"
                                />
                                <TextInput
                                    id="twitter_api_key"
                                    v-model="form.twitter_api_key"
                                    type="password"
                                    class="mt-1 block w-full"
                                />
                                <InputError
                                    :message="form.errors.twitter_api_key"
                                    class="mt-2"
                                />
                            </div>

                            <div class="col-span-6 sm:col-span-4">
                                <InputLabel
                                    for="instagram_api_key"
                                    value="Instagram Api Key"
                                />
                                <TextInput
                                    id="instagram_api_key"
                                    v-model="form.instagram_api_key"
                                    type="password"
                                    class="mt-1 block w-full"
                                />
                                <InputError
                                    :message="form.errors.instagram_api_key"
                                    class="mt-2"
                                />
                            </div>

                            <div class="col-span-6 sm:col-span-4">
                                <InputLabel
                                    for="gpt_api_key"
                                    value="GPT Api Key"
                                />
                                <TextInput
                                    id="gpt_api_key"
                                    v-model="form.gpt_api_key"
                                    type="password"
                                    class="mt-1 block w-full"
                                />
                                <InputError
                                    :message="form.errors.gpt_api_key"
                                    class="mt-2"
                                />
                            </div>
                        </template>

                        <template #actions>
                            <ActionMessage
                                :on="form.recentlySuccessful"
                                class="mr-3"
                            >
                                Saved.
                            </ActionMessage>

                            <PrimaryButton
                                :class="{ 'opacity-25': form.processing }"
                                :disabled="form.processing"
                            >
                                Save
                            </PrimaryButton>
                        </template>
                    </FormSection>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
