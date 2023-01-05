<script setup>
import { ref } from "vue";

const props = defineProps({
    modelValue: String,
});

defineEmits(["update:modelValue"]);

const input = ref(null);
</script>

<template>
    <input
        ref="input"
        class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
        type="time"
        :value="modelValue"
        step="60"
        @input="$emit('update:modelValue', $event.target.value)"
    />
</template>
