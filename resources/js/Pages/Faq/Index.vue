<script setup>
import PublicLayout from "@/Layouts/PublicLayout.vue";
import Accordion from "@/Components/Accordion.vue";

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

            <div class="space-y-4">
                <Accordion
                    v-for="faq in faqs"
                    :key="faq.id"
                    :title="faq.question"
                    :default-open="false"
                >
                    <p class="text-white/80">
                        {{ faq.answer }}
                    </p>
                </Accordion>
            </div>
        </div>
    </PublicLayout>
</template>
