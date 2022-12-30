<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import TweetCard from "../../Components/TweetCard.vue";

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
                <div class="grid grid-cols-3 gap-8">
                    <div class="flex flex-col space-y-4">
                        <h3>Drafts</h3>
                        <TweetCard
                            v-for="tweet in props.drafts"
                            :tweet="tweet"
                        />
                    </div>
                    <div class="flex flex-col space-y-4">
                        <h3>Scheduled</h3>
                        <TweetCard
                            v-for="tweet in props.scheduled"
                            :tweet="tweet"
                        />
                    </div>
                    <div class="flex flex-col space-y-4">
                        <h3>Posted</h3>
                        <TweetCard
                            v-for="tweet in props.posted"
                            :tweet="tweet"
                        />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
