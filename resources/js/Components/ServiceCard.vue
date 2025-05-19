<script setup>
defineProps({
    title: {
        type: String,
        required: true,
    },
    iconComponent: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <div
        class="p-8 border border-gaming-red rounded-lg service-card group transition-all duration-300"
    >
        <div class="service-icon mb-6 text-led-green">
            <div v-html="iconComponent" class="h-12 w-12 mx-auto"></div>
        </div>
        <h3
            class="text-2xl font-play text-led-green mb-4 text-center group-hover:text-gaming-red transition-colors duration-300"
        >
            {{ title }}
        </h3>
        <p class="text-white text-center">
            <slot></slot>
        </p>
    </div>
</template>

<style scoped>
.service-card {
    position: relative;
    overflow: hidden;
    background: rgba(0, 0, 0, 0.2);
    transform: translateY(0);
    transition: all 0.3s ease;
}

.service-card::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: radial-gradient(
        circle at center,
        rgba(236, 64, 122, 0.1),
        transparent
    );
    opacity: 0;
    transition: opacity 0.3s ease;
    z-index: -1;
}

.service-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2), 0 0 15px rgba(236, 64, 122, 0.3);
    border-color: rgba(236, 64, 122, 0.8);
}

.service-card:hover::before {
    opacity: 1;
}

.service-icon {
    transition: transform 0.3s ease;
}

.service-card:hover .service-icon {
    transform: scale(1.1);
}
</style>
