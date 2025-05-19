<script setup>
defineProps({
    title: {
        type: String,
        required: true,
    },
    subtitle: {
        type: String,
        default: "",
    },
    backgroundImage: {
        type: String,
        default: "/images/background.jpg",
    },
    height: {
        type: String,
        default: "min-h-50vh", // 50vh par défaut, 'min-h-91vh' pour la home
    },
});
</script>

<template>
    <div
        class="hero-section"
        :class="height"
        :style="{
            backgroundImage: `linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.85)), url('${backgroundImage}')`,
        }"
    >
        <div class="hero-content z-10 relative text-center">
            <h1
                class="text-6xl md:text-7xl font-play text-gaming-red mb-6 hero-title"
            >
                {{ title }}
            </h1>
            <p
                v-if="subtitle"
                class="text-2xl text-white mb-12 max-w-3xl mx-auto font-medium text-shadow-lg"
            >
                {{ subtitle }}
            </p>
            <slot name="actions"></slot>
        </div>
    </div>
</template>

<style scoped>
.hero-section {
    position: relative;
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    padding: 2rem;
    margin-bottom: 2rem;
}

.min-h-50vh {
    min-height: 50vh;
}

.min-h-91vh {
    min-height: 91vh;
}

.hero-content {
    width: 100%;
    max-width: 1200px;
    padding: 3rem 1.5rem;
    animation: fadeIn 1.2s ease-out;
}

.text-shadow-lg {
    text-shadow: 0 2px 8px rgba(0, 0, 0, 0.8);
}

/* Hero title avec animation glow */
.hero-title {
    text-shadow: 0 0 15px rgba(236, 64, 122, 0.7), 0 3px 10px rgba(0, 0, 0, 0.8);
    letter-spacing: 1px;
    font-weight: bold;
    animation: glow 2s ease-in-out infinite alternate;
}

/* Animation pour le titre */
@keyframes glow {
    from {
        text-shadow: 0 0 15px rgba(236, 64, 122, 0.7),
            0 3px 10px rgba(0, 0, 0, 0.8);
    }
    to {
        text-shadow: 0 0 25px rgba(236, 64, 122, 0.9),
            0 3px 10px rgba(0, 0, 0, 0.8);
    }
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@media (max-width: 768px) {
    .hero-title {
        font-size: 3rem;
    }
}
</style>
