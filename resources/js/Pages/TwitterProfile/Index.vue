<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const props = defineProps({
    profiles: Array,
});
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
                <ul class="flex flex-col space-y-4">
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
                            <PrimaryButton>Delete</PrimaryButton>
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
</template>
