<script setup>
import { computed } from "vue";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    href: String,
    active: Boolean,
});

const classes = computed(() => {
    return props.active
        ? "inline-flex items-center px-3 pt-1 border-b-2 border-led-green text-sm font-medium leading-5 text-white focus:outline-none focus:border-led-green transition duration-150 ease-in-out"
        : "inline-flex items-center px-3 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-300 hover:text-white hover:border-gray-300 focus:outline-none focus:text-white focus:border-gray-300 transition duration-150 ease-in-out";
});
</script>

<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>
