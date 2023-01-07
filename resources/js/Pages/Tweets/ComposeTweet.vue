<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { useForm } from "@inertiajs/inertia-vue3";
import FormSection from "@/Components/FormSection.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import LinkButton from "@/Components/LinkButton.vue";
import TextareaInput from "@/Components/TextareaInput.vue";
import ActionMessage from "@/Components/ActionMessage.vue";
import { ref } from "vue";
import Media from "./Partials/Media.vue";
import { Inertia } from "@inertiajs/inertia";
import ThreadInfo from "./Partials/ThreadInfo.vue";
import SectionBorder from "@/Components/SectionBorder.vue";
import ConfirmationModal from "@/Components/ConfirmationModal.vue";
import DangerButton from "@/Components/DangerButton.vue";

const props = defineProps({
    thread: Object,
    tweets: Array,
    profiles: Array,
    selectedProfileId: Number,
});

const form = useForm({
    _method: "PUT",
    tweets: props.tweets,
});

const timeout = ref(0);

const updateTweets = () => {
    clearTimeout(timeout.value);
    timeout.value = setTimeout(() => {
        form.put(route("compose.update", props.thread.id), {
            preserveScroll: true,
        });
    }, 300);
};

const addReply = async () => {
    const res = await window.axios.post(
        route("compose.add-reply", props.thread.id),
        {
            errorBag: "addReply",
            preserveScroll: true,
        }
    );
    form.tweets.push({
        id: res.data.id,
        content: "",
        media: [],
    });
};

const refreshPage = () => {
    Inertia.visit(route("compose.index", props.thread.id), {
        preserveScroll: true,
    });
};

const confirmingTweetDeletion = ref(false);
const tweetIdToDelete = ref(0);
const confirmTweetDeletion = (tweetId) => {
    tweetIdToDelete.value = tweetId;
    confirmingTweetDeletion.value = true;
};
const deleteTweetForm = useForm({
    _method: "DELETE",
});
const deleteTweet = () => {
    deleteTweetForm.delete(
        route("compose.tweet.destroy", tweetIdToDelete.value),
        {
            preserveScroll: true,
            onSuccess: () => {
                confirmingTweetDeletion.value = false;
                Inertia.visit(route("compose.index", props.thread.id), {
                    preserveScroll: true,
                });
            },
        }
    );
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
                    <ThreadInfo
                        :thread="props.thread"
                        :profiles="props.profiles"
                        :selected-profile-id="props.selectedProfileId"
                        @update:thread="refreshPage"
                    />
                </div>
                <SectionBorder />
                <div>
                    <FormSection class="mt-10 sm:mt-0">
                        <template #title> Compose your next Tweet </template>

                        <template #description>
                            Compose your tweet in this field. To add more tweets
                            to the thread, click the 'Add' button. You can also
                            include media such as images or videos in your tweet
                            by clicking the 'Add Media' button.
                        </template>

                        <template #form>
                            <div
                                v-for="(tweet, index) in form.tweets"
                                class="col-span-6 sm:col-span-4"
                            >
                                <div class="flex items-center justify-between">
                                    <InputLabel
                                        :for="`tweet-${index}`"
                                        :value="`Tweet ${index + 1}/${
                                            form.tweets.length
                                        }`"
                                    />
                                    <LinkButton
                                        @click="confirmTweetDeletion(tweet.id)"
                                        v-show="
                                            thread.status === 'draft' &&
                                            form.tweets.length > 1
                                        "
                                        >Delete</LinkButton
                                    >
                                </div>
                                <TextareaInput
                                    :id="`tweet-${index}`"
                                    v-model="tweet.content"
                                    type="text"
                                    class="mt-1 block w-full"
                                    @input="updateTweets"
                                    :readonly="thread.status !== 'draft'"
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

                                <Media
                                    :tweet="tweet"
                                    @update-media="refreshPage"
                                    :readonly="thread.status !== 'draft'"
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

                            <SecondaryButton
                                @click="addReply()"
                                v-show="thread.status === 'draft'"
                                >Add</SecondaryButton
                            >
                        </template>
                    </FormSection>
                </div>
            </div>
        </div>
    </AppLayout>
    <ConfirmationModal
        :show="confirmingTweetDeletion"
        @close="confirmingTweetDeletion = false"
    >
        <template #title> Delete Tweet </template>

        <template #content>
            Are you sure you want to delete this tweet? Once it is deleted, all
            of its resources and data will be permanently deleted.
        </template>

        <template #footer>
            <SecondaryButton @click.native="confirmingTweetDeletion = false">
                Nevermind
            </SecondaryButton>

            <DangerButton
                class="ml-2"
                @click.native="deleteTweet()"
                :class="{ 'opacity-25': deleteTweetForm.processing }"
                :disabled="deleteTweetForm.processing"
            >
                Delete Tweet
            </DangerButton>
        </template>
    </ConfirmationModal>
</template>
