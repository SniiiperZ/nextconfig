<script setup>
import { ArrowUpIcon, ArrowDownIcon } from "@heroicons/vue/24/outline";

defineProps({
    sortBy: {
        type: String,
        required: true,
    },
    sortDirection: {
        type: String,
        required: true,
    },
    columns: {
        type: Array,
        required: true,
        // Format: [{ key: 'name', label: 'Nom', colSpan: 3 }]
    },
});

const emit = defineEmits(["sort"]);

const toggleSort = (column) => {
    emit("sort", column);
};
</script>

<template>
    <!-- En-têtes du tableau - visible uniquement sur tablette/desktop -->
    <div
        class="hidden md:grid bg-gaming-red/20 p-4 rounded-lg mb-4 grid-cols-12 gap-4 items-center font-semibold text-white"
    >
        <div
            v-for="column in columns"
            :key="column.key"
            :class="`col-span-${
                column.colSpan || 1
            } flex items-center cursor-pointer`"
            @click="toggleSort(column.key)"
        >
            {{ column.label }}
            <ArrowUpIcon
                v-if="sortBy === column.key && sortDirection === 'asc'"
                class="h-4 w-4 ml-1"
            />
            <ArrowDownIcon
                v-else-if="sortBy === column.key && sortDirection === 'desc'"
                class="h-4 w-4 ml-1"
            />
        </div>
    </div>

    <!-- Barre de tri mobile visible uniquement sur petit écran -->
    <div class="md:hidden bg-gaming-red/20 p-3 rounded-lg mb-4 text-white">
        <div class="flex justify-between items-center">
            <div class="text-sm font-medium">Trier par:</div>
            <div class="flex gap-2 flex-wrap">
                <button
                    v-for="column in columns"
                    :key="column.key"
                    @click="toggleSort(column.key)"
                    class="px-2 py-1 rounded text-sm flex items-center"
                    :class="{
                        'bg-led-green text-deep-black': sortBy === column.key,
                        'bg-deep-black/30': sortBy !== column.key,
                    }"
                >
                    {{ column.label }}
                    <ArrowUpIcon
                        v-if="sortBy === column.key && sortDirection === 'asc'"
                        class="h-3 w-3 ml-1"
                    />
                    <ArrowDownIcon
                        v-else-if="
                            sortBy === column.key && sortDirection === 'desc'
                        "
                        class="h-3 w-3 ml-1"
                    />
                </button>
            </div>
        </div>
    </div>
</template>
