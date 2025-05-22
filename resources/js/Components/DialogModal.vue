<script setup>
import Modal from "./Modal.vue";

const emit = defineEmits(["close"]);

defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    maxWidth: {
        type: String,
        default: "2xl",
    },
    closeable: {
        type: Boolean,
        default: true,
    },
});

const close = () => {
    emit("close");
};
</script>

<template>
    <Modal
        :show="show"
        :max-width="maxWidth"
        :closeable="closeable"
        @close="close"
    >
        <div class="px-6 py-4 bg-deep-black text-white">
            <div class="text-lg font-play font-medium text-led-green">
                <slot name="title" />
            </div>

            <div class="mt-4 text-sm text-white/80">
                <slot name="content" />
            </div>
        </div>

        <div
            class="flex flex-row justify-end px-6 py-4 bg-deep-black/80 border-t border-gaming-red/30 text-end"
        >
            <slot name="footer" />
        </div>
    </Modal>
</template>
