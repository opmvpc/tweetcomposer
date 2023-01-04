<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Link } from "@inertiajs/inertia-vue3";
import SectionTitle from "@/Components/SectionTitle.vue";
import SelectTwitterProfile from "./TwitterProfile/Partials/SelectTwitterProfile.vue";

const props = defineProps({
    profiles: Array,
    selectedProfileId: Number,
});
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="grid grid-cols-2 gap-20">
                    <div>
                        <SectionTitle>
                            <template #title> Twitter profiles </template>
                            <template #description></template>
                        </SectionTitle>
                        <SelectTwitterProfile
                            :profiles="props.profiles"
                            :selectedProfileId="props.selectedProfileId"
                        />
                        <div class="mt-4">
                            <Link
                                :href="route('twitter-profile.index')"
                                class="font-bold"
                            >
                                Manage your profiles
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
