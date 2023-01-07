<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SectionTitle from "@/Components/SectionTitle.vue";
import { ref } from "vue";
import { useForm } from "@inertiajs/inertia-vue3";
import ConfirmationModal from "@/Components/ConfirmationModal.vue";
import DangerButton from "@/Components/DangerButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";

const props = defineProps({
    profiles: Array,
});

const confirmingProfileDeletion = ref(false);
const profileIdToDelete = ref(0);
const confirmProfileDeletion = (tweetId) => {
    profileIdToDelete.value = tweetId;
    confirmingProfileDeletion.value = true;
};
const deleteProfileForm = useForm({
    _method: "DELETE",
});

const deleteProfile = () => {
    deleteProfileForm.delete(
        route("twitter-profile.destroy", profileIdToDelete.value),
        {
            preserveScroll: true,
            onSuccess: () => {
                confirmingProfileDeletion.value = false;
            },
        }
    );
};
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Twitter profiles
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <SectionTitle>
                    <template #title> Twitter profiles </template>
                    <template #description>
                        Manage your Twitter profiles here. You can add as many
                        as needed.
                    </template>
                </SectionTitle>

                <div
                    v-if="props.profiles.length === 0"
                    class="bg-green-200 text-green-700 shadow rounded-lg p-4 mt-8"
                >
                    <p class="mb-2 text-lg">👋 Welcome !</p>
                    <p>
                        To get started, you will need to add a Twitter profile.
                        <br />
                        Please click on the button below 👇.
                    </p>
                </div>

                <ul class="flex flex-col space-y-6 mt-8">
                    <li
                        v-for="profile in props.profiles"
                        class="bg-white rounded-full flex shadow-md space-x-2 pr-6"
                    >
                        <img
                            class="rounded-full border-4 border-gray-300 h-16 w-16"
                            :src="profile.avatar"
                            alt="twitter avatar"
                        />
                        <div class="font-bold flex flex-col justify-center">
                            @{{ profile.nickname }}
                        </div>
                        <div class="flex-grow flex items-center justify-end">
                            <PrimaryButton
                                @click="confirmProfileDeletion(profile.id)"
                                >Delete</PrimaryButton
                            >
                        </div>
                    </li>

                    <a
                        :href="route('twitter-profile.create')"
                        class="h-16 bg-white rounded-full flex shadow-md items-center justify-center font-bold"
                    >
                        Add a new profile
                    </a>
                </ul>
            </div>
        </div>
    </AppLayout>
    <ConfirmationModal
        :show="confirmingProfileDeletion"
        @close="confirmingProfileDeletion = false"
    >
        <template #title> Delete Twitter profile </template>

        <template #content>
            Are you sure you want to delete this profile? Once it is deleted,
            all of its resources and data will be permanently deleted.
        </template>

        <template #footer>
            <SecondaryButton @click.native="confirmingProfileDeletion = false">
                Nevermind
            </SecondaryButton>

            <DangerButton
                class="ml-2"
                @click.native="deleteProfile()"
                :class="{ 'opacity-25': deleteProfileForm.processing }"
                :disabled="deleteProfileForm.processing"
            >
                Delete profile
            </DangerButton>
        </template>
    </ConfirmationModal>
</template>
