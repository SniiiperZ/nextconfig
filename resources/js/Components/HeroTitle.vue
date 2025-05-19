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
        default: "mb-10",
    },
});
</script>

<template>
    <div :class="[centered ? 'flex justify-center' : '', marginBottom]">
        <h2 class="text-4xl font-play text-gaming-red section-title">
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
</style>
