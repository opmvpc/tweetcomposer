<script setup>
import { useForm } from "@inertiajs/inertia-vue3";
import { ref } from "vue";
import FormSection from "../../../Components/FormSection.vue";
import InputLabel from "../../../Components/InputLabel.vue";
import ActionMessage from "../../../Components/ActionMessage.vue";

const props = defineProps({
    threadId: Number,
    profiles: Array,
    selectedProfileId: Number,
});

const form = useForm({
    _method: "PUT",
    selectedProfileId: props.selectedProfileId,
});

const updatethread = () => {
    form.put(route("threads.update", props.threadId), {
        preserveScroll: true,
    });
};

const updateSelectedProfileId = (selectedProfileId) => {
    form.selectedProfileId = selectedProfileId;
    updatethread();
};

const defaultClasses = ref("shadow");

const selectedClasses = ref("shadow-md border-4 border-blue-200 bg-blue-50");

const isSelected = (profileId) => {
    return profileId === form.selectedProfileId;
};
</script>

<template>
    <FormSection>
        <template #title> Thread settings </template>

        <template #description>
            Choose the Twitter profile that will be used to post the thread.
        </template>

        <template #form>
            <div class="col-span-6 sm:col-span-4">
                <InputLabel value="Twitter profile" class="w-full" />
                <ul class="flex flex-col space-y-4 mt-4">
                    <button
                        @click="updateSelectedProfileId(profile.id)"
                        v-for="profile in props.profiles"
                        class="bg-white rounded-full flex space-x-2 pr-6"
                        :class="
                            isSelected(profile.id)
                                ? selectedClasses
                                : defaultClasses
                        "
                    >
                        <img
                            class="rounded-full border-4 border-gray-300 h-10 w-10"
                            :src="profile.avatar"
                            alt="twitter avatar"
                        />
                        <div class="font-bold flex flex-col justify-center">
                            @{{ profile.nickname }}
                        </div>
                    </button>
                </ul>
            </div>
        </template>

        <template #actions>
            <ActionMessage :on="form.recentlySuccessful" class="mr-3">
                Saved.
            </ActionMessage>
        </template>
    </FormSection>
</template>
