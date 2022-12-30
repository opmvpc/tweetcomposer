<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { useForm } from "@inertiajs/inertia-vue3";
import FormSection from "@/Components/FormSection.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import TextareaInput from "@/Components/TextareaInput.vue";
import ActionMessage from "@/Components/ActionMessage.vue";
import { ref } from "vue";

const props = defineProps({
    tweets: Array,
});

const form = useForm({
    _method: "PUT",
    tweets: props.tweets,
});

const timeout = ref(0);

const updateTweets = () => {
    clearTimeout(timeout.value);
    timeout.value = setTimeout(() => {
        form.put(route("compose.update"), {
            preserveScroll: true,
        });
    }, 500);
};

const addReply = async (id) => {
    const res = await window.axios.post(route("compose.add-reply", id), {
        errorBag: "addReply",
        preserveScroll: true,
    });
    form.tweets.push({
        id: res.data.id,
        content: "",
    });
};
</script>

<template>
    <AppLayout title="Compose">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Compose
            </h2>
        </template>

        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <div>
                    <FormSection>
                        <template #title> Compose your next Tweet </template>

                        <template #description>
                            Write your tweet here. Click on the "Add" button to
                            add more tweets.
                        </template>

                        <template #form>
                            <div
                                v-for="(tweet, index) in form.tweets"
                                class="col-span-6 sm:col-span-4"
                            >
                                <InputLabel
                                    :for="`tweet-${index}`"
                                    :value="`Tweet ${index + 1}/${
                                        form.tweets.length
                                    }`"
                                />
                                <TextareaInput
                                    :id="`tweet-${index}`"
                                    v-model="tweet.content"
                                    type="text"
                                    class="mt-1 block w-full"
                                    @input="updateTweets"
                                />
                                <div class="grid grid-cols-3 mt-2">
                                    <div class="col-span-2">
                                        <InputError
                                            :message="
                                                form.errors[
                                                    `tweets.${index}.content`
                                                ]
                                            "
                                        />
                                    </div>
                                    <div class="text-sm text-right">
                                        {{ tweet.content?.length ?? 0 }}
                                        / {{ 280 }}
                                    </div>
                                </div>
                            </div>
                        </template>

                        <template #actions>
                            <ActionMessage
                                :on="form.recentlySuccessful"
                                class="mr-3"
                            >
                                Saved.
                            </ActionMessage>

                            <SecondaryButton
                                @click="addReply(tweets[tweets.length - 1])"
                                class="mr-3"
                                >Add</SecondaryButton
                            >
                        </template>
                    </FormSection>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
