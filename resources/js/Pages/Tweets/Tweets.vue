<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import ThreadCard from "./Partials/ThreadCard.vue";

const props = defineProps({
    drafts: Array,
    scheduled: Array,
    posted: Array,
});
</script>

<template>
    <AppLayout title="Tweets">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Tweets
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-8">
                    <div class="flex flex-col space-y-4">
                        <h3 class="text-lg font-medium text-gray-900">
                            Drafts
                        </h3>
                        <ThreadCard
                            v-for="thread in props.drafts"
                            :thread="thread"
                        />
                    </div>
                    <div class="flex flex-col space-y-4">
                        <h3 class="text-lg font-medium text-gray-900">
                            Scheduled
                        </h3>
                        <ThreadCard
                            v-for="thread in props.scheduled"
                            :thread="thread"
                        />
                    </div>
                    <div class="flex flex-col space-y-4">
                        <h3 class="text-lg font-medium text-gray-900">
                            Posted
                        </h3>
                        <ThreadCard
                            v-for="thread in props.posted"
                            :thread="thread"
                        />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
