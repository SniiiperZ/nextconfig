<script setup>
import { computed } from "vue";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    active: Boolean,
    href: String,
    as: String,
});

const classes = computed(() => {
    return props.active
        ? "block w-full ps-3 pe-4 py-2 border-l-4 border-led-green text-start text-base font-medium text-white bg-deep-black/30 focus:outline-none focus:text-white focus:bg-deep-black/50 focus:border-led-green transition duration-150 ease-in-out"
        : "block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-300 hover:text-white hover:bg-deep-black/20 hover:border-gray-300 focus:outline-none focus:text-white focus:bg-deep-black/20 focus:border-gray-300 transition duration-150 ease-in-out";
});
</script>

<template>
    <div>
        <button
            v-if="as == 'button'"
            :class="classes"
            class="w-full text-start"
        >
            <slot />
        </button>

        <a
            v-else-if="as == 'a'"
            :class="classes"
            class="w-full text-start"
            :href="href"
        >
            <slot />
        </a>

        <Link v-else :href="href" :class="classes">
            <slot />
        </Link>
    </div>
</template>
