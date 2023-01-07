<script setup>
import { useForm } from "@inertiajs/inertia-vue3";
import InputError from "@/Components/InputError.vue";
import { TrashIcon } from "@heroicons/vue/24/outline";

const props = defineProps({
    tweet: Object,
    readonly: {
        type: Boolean,
        default: false,
    },
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

const deleteMediaForm = useForm({
    _method: "DELETE",
});

const deleteMedia = (mediumId) => {
    deleteMediaForm.delete(route("media.destroy", mediumId), {
        onSuccess: () => {
            emit("updateMedia");
        },
        preserveScroll: true,
    });
};
</script>

<template>
    <div class="flex flex-col">
        <div class="grid grid-cols-4 gap-4 mt-2">
            <div
                class="group h-20 relative shadow hover:shadow-md rounded cursor-pointer overflow-hidden"
                v-for="medium in tweet.media"
            >
                <img
                    class="absolute w-full aspect-square group-hover:opacity-30 transition-all"
                    @click="deleteMedia(medium.id)"
                    :src="medium.url"
                />
                <div
                    class="h-full w-full items-center justify-center hidden group-hover:flex transition-all"
                >
                    <TrashIcon
                        class="absolute text-red-600 cursor-pointer h-6 w-6 transition-all"
                        @click="deleteMedia(medium.id)"
                    />
                </div>
            </div>
            <div
                v-show="!readonly"
                class="bg-gray-100 text-xs flex items-center justify-center rounded shadow cursor-pointer p-2 text-center aspect-square hover:bg-gray-200 text-gray-800 hover:text-black active:bg-gray-300 hover:shadow-md active:text-black transition-all"
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
