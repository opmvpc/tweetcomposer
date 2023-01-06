<script setup>
import { useForm } from "@inertiajs/inertia-vue3";
import { ref } from "vue";
import FormSection from "@/Components/FormSection.vue";
import InputLabel from "@/Components/InputLabel.vue";
import ActionMessage from "@/Components/ActionMessage.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Checkbox from "@/Components/Checkbox.vue";
import DateInput from "@/Components/DateInput.vue";
import TimeInput from "@/Components/TimeInput.vue";
import InputError from "@/Components/InputError.vue";

const emit = defineEmits(["update:thread"]);

const props = defineProps({
    thread: Object,
    profiles: Array,
    selectedProfileId: Number,
});

let scheduledAt = new Date(props.thread.scheduled_at);
scheduledAt = new Date(scheduledAt - scheduledAt.getTimezoneOffset() * 60000);
const date = scheduledAt.toISOString().split("T")[0];
const time = scheduledAt
    .toISOString()
    .split("T")[1]
    .split(".")[0]
    .substring(0, 5);

const form = useForm({
    _method: "PUT",
    selectedProfileId: props.selectedProfileId,
    postAsThread: props.thread.post_as_thread,
    scheduledAtDate: date,
    scheduledAtTime: time,
});

const formUpdateStatus = useForm({
    _method: "PUT",
    status: props.thread.status,
});

const updateStatus = (status) => {
    formUpdateStatus.status = status;
    formUpdateStatus.put(route("threads.status.update", props.thread.id), {
        preserveScroll: true,
        onSuccess: () => {
            emit("update:thread");
        },
    });
};

const updateThread = () => {
    if (formUpdateStatus.status !== "scheduled") {
        form.scheduledAtDate = null;
        form.scheduledAtTime = null;
    }
    form.put(route("threads.update", props.thread.id), {
        preserveScroll: true,
    });
};

const updateSelectedProfileId = (selectedProfileId) => {
    form.selectedProfileId = selectedProfileId;
    updateThread();
};

const defaultClasses = ref("shadow");

const selectedClasses = ref(
    "shadow-md border-4 border-indigo-300 bg-indigo-50"
);

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
                        :disabled="thread.status !== 'draft'"
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

            <div class="col-span-6 sm:col-span-4 flex items-center space-x-2">
                <Checkbox
                    id="post-as-thread"
                    v-model:checked="form.postAsThread"
                    @update:checked="updateThread"
                    :disabled="thread.status !== 'draft'"
                />
                <InputLabel value="Post as thread" for="post-as-thread" />
            </div>

            <div
                class="col-span-6 sm:col-span-4"
                v-show="thread.status !== 'draft'"
            >
                <InputLabel
                    value="Scheduled at"
                    for="scheduled-at-date"
                    class="mb-2"
                />
                <DateInput
                    class="mr-2"
                    id="scheduled-at-date"
                    v-model="form.scheduledAtDate"
                    @change="updateThread"
                    :readonly="thread.status !== 'scheduled'"
                />

                <TimeInput
                    id="scheduled-at-time"
                    v-model="form.scheduledAtTime"
                    @change="updateThread"
                    :readonly="thread.status !== 'scheduled'"
                />

                <InputError
                    :message="form.errors.scheduledAtDate"
                    class="mt-2"
                />
                <InputError
                    :message="form.errors.scheduledAtTime"
                    class="mt-2"
                />
            </div>
        </template>

        <template #actions>
            <div class="flex items-center justify-end space-x-3">
                <ActionMessage :on="form.recentlySuccessful">
                    Saved.
                </ActionMessage>
                <SecondaryButton>Delete</SecondaryButton>
                <PrimaryButton
                    @click="updateStatus('scheduled')"
                    v-if="formUpdateStatus.status === 'draft'"
                    >Publish</PrimaryButton
                >
                <SecondaryButton
                    @click="updateStatus('draft')"
                    v-if="formUpdateStatus.status === 'scheduled'"
                    >Draft</SecondaryButton
                >
                <PrimaryButton
                    @click="updateStatus('posted')"
                    v-if="formUpdateStatus.status === 'scheduled'"
                    >Post</PrimaryButton
                >
            </div>
        </template>
    </FormSection>
</template>
