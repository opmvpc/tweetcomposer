<script setup>
import { Link } from "@inertiajs/inertia-vue3";
import { defineProps } from "vue";
import { computed } from "vue";

const props = defineProps({
    tweet: Object,
});

const formatedScheduledAt = computed(() => {
    return new Date(props.tweet.scheduled_at).toLocaleString();
});

const formatedUpdatedAt = computed(() => {
    return new Date(props.tweet.updated_at).toLocaleString();
});
</script>

<template>
    <Link
        :href="route('compose.index', [tweet.id])"
        class="bg-white p-4 rounded shadow flex flex-col space-y-2"
    >
        <div>{{ tweet.content }}</div>
        <div class="text-xs" v-if="tweet.scheduled_at">
            {{ formatedScheduledAt }}
        </div>
        <div class="text-xs" v-else>{{ formatedUpdatedAt }}</div>
    </Link>
</template>
