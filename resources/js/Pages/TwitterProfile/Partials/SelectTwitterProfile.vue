<script setup>
import { useForm } from "@inertiajs/inertia-vue3";
import { ref } from "vue";

const props = defineProps({
    profiles: Array,
    selectedProfileId: Number,
});

const defaultClasses = ref("shadow");

const selectedClasses = ref(
    "shadow-md border-4 border-indigo-300 bg-indigo-50"
);

const isSelected = (profileId) => {
    return profileId === form.selectedProfileId;
};

const form = useForm({
    _method: "PUT",
    selectedProfileId: props.selectedProfileId,
});

const updateSelectedProfileId = (selectedProfileId) => {
    form.selectedProfileId = selectedProfileId;
    form.put(route("twitter-profile.select.update"), {
        preserveScroll: true,
    });
};
</script>

<template>
    <ul class="flex flex-col space-y-4 mt-4">
        <button
            @click="updateSelectedProfileId(profile.id)"
            v-for="profile in props.profiles"
            class="bg-white rounded-full flex space-x-2 pr-6"
            :class="isSelected(profile.id) ? selectedClasses : defaultClasses"
        >
            <img
                class="rounded-full border-4 border-gray-300 h-16 w-16"
                :src="profile.avatar"
                alt="twitter avatar"
            />
            <div class="font-bold flex flex-col justify-center">
                @{{ profile.nickname }}
            </div>
        </button>
    </ul>
</template>
