<script setup>
import PublicLayout from "@/Layouts/PublicLayout.vue";

defineProps({
    faqs: Array,
});
</script>

<template>
    <PublicLayout title="FAQ">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl font-play font-bold text-center mb-12">
                Questions Fréquemment Posées
            </h1>

            <div class="space-y-8">
                <div
                    v-for="faq in faqs"
                    :key="faq.id"
                    class="bg-deep-black border border-gaming-red rounded-lg p-6"
                >
                    <h3
                        class="text-xl font-play font-semibold text-led-green mb-4"
                    >
                        {{ faq.question }}
                    </h3>
                    <p class="text-white/80">
                        {{ faq.answer }}
                    </p>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>
