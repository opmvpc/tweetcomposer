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
    gpt_api_key: props.user.gpt_api_key,
});

const updateSocialKeys = () => {
    form.post(route("external-api-key.update"), {
        preserveScroll: true,
    });
};
</script>

<template>
    <AppLayout title="External Api keys">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                External Api Secrets
            </h2>
        </template>

        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <div>
                    <FormSection @submitted="updateSocialKeys">
                        <template #title> Api keys </template>

                        <template #description>
                            Enter your external API keys here. These can be
                            obtained from your developer account.
                        </template>

                        <template #form>
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
