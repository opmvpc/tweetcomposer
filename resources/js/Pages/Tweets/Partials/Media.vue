<script setup>
import { useForm } from '@inertiajs/inertia-vue3';

const props = defineProps({
    tweet: Object,
});

const form = useForm({
    _method: 'PUT',
    media: props.tweet.media,
});
</script>
<template>
    <!-- Media -->
    <div class="grid grid-cols-4 gap-4 mt-2">
        <div v-for="medium in tweet.media">
            <img
                class="h-12 rounded shadow object-cover object-center"
                :src="medium.url"
            />
        </div>
        <div
            class="bg-gray-200 text-xs flex items-center justify-center h-12 rounded shadow cursor-pointer p-2 text-center"
        >
            <div>Add media</div>
        </div>
    </div>
</template>
