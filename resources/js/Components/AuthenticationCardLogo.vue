<script setup>
import { Link } from "@inertiajs/inertia-vue3";
import logoUrl from "../../img/logo.jpg";
</script>

<template>
    <Link
        :href="'/'"
        class="block h-64 w-64 rounded-full border-[12px] border-indigo-300 shadow-2xl shadow-indigo-300 overflow-hidden mb-10"
    >
        <img class="object-cover object-center" :src="logoUrl" alt="logo" />
    </Link>
</template>
