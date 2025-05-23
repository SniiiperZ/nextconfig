<script setup>
import {
    TransitionRoot,
    TransitionChild,
    Dialog,
    DialogPanel,
} from "@headlessui/vue";
import { ExclamationTriangleIcon } from "@heroicons/vue/24/outline";

defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    itemName: {
        type: String,
        required: true,
    },
    itemLabel: {
        type: String,
        default: "élément",
    },
});

const emit = defineEmits(["close", "confirm"]);

const closeModal = () => {
    emit("close");
};

const confirmDelete = () => {
    emit("confirm");
};
</script>

<template>
    <TransitionRoot appear :show="show" as="template">
        <Dialog as="div" @close="closeModal" class="relative z-10">
            <TransitionChild
                as="template"
                enter="duration-300 ease-out"
                enter-from="opacity-0"
                enter-to="opacity-100"
                leave="duration-200 ease-in"
                leave-from="opacity-100"
                leave-to="opacity-0"
            >
                <div class="fixed inset-0 bg-black/75 transition-opacity" />
            </TransitionChild>

            <div class="fixed inset-0 overflow-y-auto">
                <div
                    class="flex min-h-full items-center justify-center p-4 text-center"
                >
                    <TransitionChild
                        as="template"
                        enter="duration-300 ease-out"
                        enter-from="opacity-0 scale-95"
                        enter-to="opacity-100 scale-100"
                        leave="duration-200 ease-in"
                        leave-from="opacity-100 scale-100"
                        leave-to="opacity-0 scale-95"
                    >
                        <DialogPanel
                            class="w-full max-w-md transform overflow-hidden rounded-2xl bg-deep-black border border-gaming-red p-4 sm:p-6 text-left align-middle shadow-xl transition-all"
                        >
                            <div
                                class="flex items-center justify-center mb-5 text-gaming-red"
                            >
                                <ExclamationTriangleIcon
                                    class="h-10 w-10 sm:h-12 sm:w-12"
                                />
                            </div>

                            <div class="text-center">
                                <h3
                                    class="text-lg sm:text-xl font-medium text-white mb-4"
                                >
                                    Confirmer la suppression
                                </h3>
                                <p
                                    class="text-white/70 mb-6 text-sm sm:text-base"
                                >
                                    Êtes-vous sûr de vouloir supprimer
                                    {{ itemLabel }} : <br />
                                    <span
                                        class="font-semibold text-led-green break-words"
                                        >{{ itemName }}</span
                                    >
                                </p>
                            </div>

                            <div class="flex justify-center gap-4 mt-6">
                                <button
                                    @click="closeModal"
                                    class="inline-flex justify-center rounded-md border border-gaming-red bg-deep-black px-3 py-2 sm:px-4 sm:py-2 text-sm sm:text-base text-white hover:bg-gaming-red/10 transition-colors"
                                >
                                    Annuler
                                </button>
                                <button
                                    @click="confirmDelete"
                                    class="inline-flex justify-center rounded-md border border-transparent bg-gaming-red px-3 py-2 sm:px-4 sm:py-2 text-sm sm:text-base text-white hover:bg-gaming-red/90 transition-colors"
                                >
                                    Supprimer
                                </button>
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>
