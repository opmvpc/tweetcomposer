<script setup>
import { ref } from "vue";

const props = defineProps({
    modelValue: String,
});

defineEmits(["update:modelValue"]);

const min = new Date().toISOString().split("T")[0];

const input = ref(null);
</script>

<template>
    <input
        ref="input"
        class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
        type="date"
        :min="min"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
    />
</template>
