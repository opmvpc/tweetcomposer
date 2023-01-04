<script setup>
import { useForm } from "@inertiajs/inertia-vue3";
import InputError from "@/Components/InputError.vue";

const props = defineProps({
    tweet: Object,
});

const emit = defineEmits(["updateMedia"]);

const form = useForm({
    _method: "POST",
    files: [],
});

const handleFilesUpload = (e) => {
    form.files = e.target.files;
    form.post(route("media.upload", props.tweet.id), {
        onSuccess: () => {
            emit("updateMedia");
        },
        progress: (e) => {
            form.progress = e;
        },
        preserveScroll: true,
    });
};
</script>

<template>
    <div class="flex flex-col">
        <div class="grid grid-cols-4 gap-4 mt-2">
            <div v-for="medium in tweet.media">
                <img
                    class="rounded shadow object-cover object-center"
                    :src="medium.url"
                />
            </div>
            <div
                v-show="tweet.media.length < 4"
                class="bg-gray-200 text-xs flex items-center justify-center min-h-12 rounded shadow cursor-pointer p-2 text-center"
                @click="$refs.fileInput.click()"
            >
                <div>Add media</div>
            </div>
        </div>
        <form @submit.prevent="submit">
            <input
                ref="fileInput"
                class="hidden"
                type="file"
                @input="handleFilesUpload"
                multiple
                accept="image/png, image/jpeg, image/gif, video/mp4"
            />
        </form>
        <InputError
            class="mt-2"
            v-for="errorMessage in form.errors"
            :message="errorMessage"
        />
    </div>
</template>
