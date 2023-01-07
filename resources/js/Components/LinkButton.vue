<script setup>
defineProps({
    type: {
        type: String,
        default: "button",
    },
});
</script>

<template>
    <button
        :type="type"
        class="inline-flex items-center text-xs text-gray-700 capitalize tracking-wide shadow-sm hover:text-gray-500 focus:outline-none focus:text-gray-600 active:text-gray-800 disabled:opacity-25 transition"
    >
        <slot />
    </button>
</template>
