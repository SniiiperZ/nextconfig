<script setup>
defineProps({
    processing: {
        type: Boolean,
        default: false,
    },
    isEditing: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(["cancel"]);

const cancelEdit = () => {
    emit("cancel");
};
</script>

<template>
    <div class="flex flex-wrap gap-4 pt-2">
        <button
            type="submit"
            class="bg-gaming-red text-white px-6 py-3 rounded-md hover:bg-gaming-red/90 transition duration-150 flex items-center"
            :disabled="processing"
        >
            <span v-if="processing" class="mr-2">
                <svg
                    class="animate-spin h-5 w-5 text-white"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                >
                    <circle
                        class="opacity-25"
                        cx="12"
                        cy="12"
                        r="10"
                        stroke="currentColor"
                        stroke-width="4"
                    ></circle>
                    <path
                        class="opacity-75"
                        fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                    ></path>
                </svg>
            </span>
            {{ isEditing ? "Mettre à jour" : "Ajouter" }}
        </button>
        <button
            v-if="isEditing"
            @click="cancelEdit"
            type="button"
            class="bg-deep-black border border-gaming-red text-white px-6 py-3 rounded-md hover:bg-gaming-red/10 transition duration-150"
        >
            Annuler
        </button>
    </div>
</template>
