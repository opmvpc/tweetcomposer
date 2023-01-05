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
        <div>
            <img
                class="float-right rounded-full border-gray-300 border-4 h-12 w-12 ml-2"
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
                        class="h-5 w-5 text-gray-500"
                    />
                    <CheckCircleIcon v-else class="h-5 w-5 text-green-600" />
                    <span>
                        {{ thread.scheduled_at_diff }}
                    </span>
                </div>
                <div
                    class="text-xs flex space-x-1 items-center"
                    v-if="thread.status === 'draft'"
                >
                    <PencilSquareIcon class="h-5 w-5 text-gray-500" />
                    <span>
                        {{ thread.updated_at_diff }}
                    </span>
                </div>
            </div>
            <div class="text-xs flex items-center space-x-1">
                <span>
                    {{ thread.tweets_count }}
                </span>
                <ChatBubbleOvalLeftEllipsisIcon class="h-5 w-5 text-gray-500" />
            </div>
        </div>
    </Link>
</template>
