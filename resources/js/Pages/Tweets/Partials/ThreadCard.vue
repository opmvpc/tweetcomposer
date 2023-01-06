<script setup>
import { Link } from "@inertiajs/inertia-vue3";
import { defineProps } from "vue";
import {
    ClockIcon,
    CheckCircleIcon,
    PencilSquareIcon,
    ChatBubbleOvalLeftEllipsisIcon,
} from "@heroicons/vue/24/outline";

const props = defineProps({
    thread: Object,
});
</script>

<template>
    <Link
        :href="route('compose.index', [thread.id])"
        class="bg-white p-4 rounded shadow flex flex-col space-y-2"
    >
        <div class="text-sm text-gray-900">
            <img
                class="float-right rounded-full border-indigo-300 border-4 h-12 w-12 ml-2"
                :src="thread.twitter_profile.avatar"
            />
            {{ thread.title }}
        </div>
        <div class="flex justify-between">
            <div>
                <div
                    class="text-xs flex space-x-1 items-center"
                    v-if="
                        thread.status === 'scheduled' ||
                        thread.status === 'posted'
                    "
                >
                    <ClockIcon
                        v-if="thread.status === 'scheduled'"
                        class="h-5 w-5 text-gray-400"
                    />
                    <CheckCircleIcon v-else class="h-5 w-5 text-green-600" />
                    <span class="text-gray-600">
                        {{ thread.scheduled_at_diff }}
                    </span>
                </div>
                <div
                    class="text-xs flex space-x-1 items-center"
                    v-if="thread.status === 'draft'"
                >
                    <PencilSquareIcon class="h-5 w-5 text-gray-400" />
                    <span class="text-gray-600">
                        {{ thread.updated_at_diff }}
                    </span>
                </div>
            </div>
            <div class="text-xs flex items-center space-x-1">
                <span class="text-gray-600 font-mono">
                    {{ thread.tweets_count }}
                </span>
                <ChatBubbleOvalLeftEllipsisIcon class="h-5 w-5 text-gray-400" />
            </div>
        </div>
    </Link>
</template>
