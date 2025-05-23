<script setup>
import { MagnifyingGlassIcon } from "@heroicons/vue/24/outline";

defineProps({
    title: {
        type: String,
        required: true,
    },
    searchQuery: {
        type: String,
        required: true,
    },
});

const emit = defineEmits(["update:searchQuery"]);

const updateSearch = (event) => {
    emit("update:searchQuery", event.target.value);
};
</script>

<template>
    <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ title }}
        </h2>
        <div class="relative w-full sm:w-auto">
            <div
                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
            >
                <MagnifyingGlassIcon
                    class="h-5 w-5 text-gray-400"
                    aria-hidden="true"
                />
            </div>
            <input
                :value="searchQuery"
                @input="updateSearch"
                type="text"
                placeholder="Rechercher..."
                class="block w-full pl-10 pr-3 py-2 border border-gaming-red rounded-md bg-deep-black/50 text-white focus:outline-none focus:ring-2 focus:ring-led-green"
            />
        </div>
    </div>
</template>
