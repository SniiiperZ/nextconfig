<script setup>
defineProps({
    title: {
        type: String,
        required: true,
    },
    image: {
        type: String,
        required: true,
    },
    imageAlt: {
        type: String,
        default: "Image",
    },
    imageRight: {
        type: Boolean,
        default: false,
    },
});
</script>

<template>
    <div
        class="bg-deep-black p-8 rounded-lg border border-gaming-red hover-card"
    >
        <div class="grid md:grid-cols-2 gap-8 items-center">
            <div v-if="!imageRight" class="animate-fade-in-left">
                <h3 class="text-2xl font-play text-led-green mb-6">
                    {{ title }}
                </h3>
                <slot></slot>
            </div>

            <div
                :class="[
                    'flex justify-center',
                    !imageRight
                        ? 'animate-fade-in-right'
                        : 'order-2 md:order-1 animate-fade-in-left',
                ]"
            >
                <div class="service-image">
                    <img
                        :src="image"
                        :alt="imageAlt"
                        class="rounded-lg shadow-glow"
                    />
                </div>
            </div>

            <div
                v-if="imageRight"
                :class="['order-1 md:order-2 animate-fade-in-right']"
            >
                <h3 class="text-2xl font-play text-led-green mb-6">
                    {{ title }}
                </h3>
                <slot></slot>
            </div>
        </div>
    </div>
</template>

<style scoped>
.hover-card {
    transition: all 0.3s ease;
}

.hover-card:hover {
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3), 0 0 15px rgba(236, 64, 122, 0.3);
    transform: translateY(-5px);
}

.service-image {
    position: relative;
    display: inline-block;
    border-radius: 8px;
    overflow: hidden;
    width: 100%;
    max-width: 500px;
    transition: all 0.3s ease;
}

.shadow-glow {
    box-shadow: 0 0 20px rgba(236, 64, 122, 0.5);
    transition: all 0.3s ease;
}

.service-image:hover .shadow-glow {
    box-shadow: 0 0 30px rgba(236, 64, 122, 0.8);
}

.animate-fade-in-left {
    animation: fadeInLeft 1s ease-out;
}

.animate-fade-in-right {
    animation: fadeInRight 1s ease-out;
}

@keyframes fadeInLeft {
    from {
        opacity: 0;
        transform: translateX(-20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes fadeInRight {
    from {
        opacity: 0;
        transform: translateX(20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}
</style>
