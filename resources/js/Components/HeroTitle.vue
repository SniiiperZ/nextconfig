<script setup>
defineProps({
    title: {
        type: String,
        required: true,
    },
    centered: {
        type: Boolean,
        default: true,
    },
    marginBottom: {
        type: String,
        default: "mb-6 sm:mb-8 md:mb-10",
    },
});
</script>

<template>
    <div :class="[centered ? 'flex justify-center' : '', marginBottom]">
        <h2
            class="text-3xl sm:text-3xl md:text-4xl font-play text-gaming-red section-title px-4"
        >
            {{ title }}
        </h2>
    </div>
</template>

<style scoped>
.section-title {
    position: relative;
    display: inline-block;
    padding-bottom: 10px;
    margin-left: auto;
    margin-right: auto;
    text-align: center;
}

.section-title::after {
    content: "";
    position: absolute;
    width: 60%;
    height: 3px;
    bottom: 0;
    left: 20%;
    background: linear-gradient(
        90deg,
        transparent,
        rgba(236, 64, 122, 0.8),
        transparent
    );
}

@media (max-width: 640px) {
    .section-title::after {
        height: 2px;
    }
}
</style>
