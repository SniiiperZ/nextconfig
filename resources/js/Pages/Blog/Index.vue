<script setup>
import PublicLayout from "@/Layouts/PublicLayout.vue";
import { Link } from "@inertiajs/vue3";
import { computed } from "vue";

const props = defineProps({
    posts: Array,
});

// Formater la date au format français
const formatDate = (dateString) => {
    if (!dateString) return "";
    const date = new Date(dateString);
    return date.toLocaleDateString("fr-FR", {
        day: "numeric",
        month: "long",
        year: "numeric",
    });
};

// Calculer la durée de lecture (environ 200 mots par minute)
const readingTime = (content) => {
    const wpm = 200;
    const words = content.trim().split(/\s+/).length;
    const time = Math.ceil(words / wpm);
    return time;
};
</script>

<template>
    <PublicLayout title="Blog">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <h1 class="text-4xl font-play text-gaming-red mb-8 text-center">
                    Blog
                </h1>

                <div
                    v-if="posts.length"
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8"
                >
                    <div
                        v-for="post in posts"
                        :key="post.id"
                        class="bg-deep-black border border-gaming-red rounded-lg overflow-hidden transition-transform hover:scale-105"
                    >
                        <!-- Image -->
                        <div class="h-48 overflow-hidden">
                            <img
                                v-if="post.featured_image"
                                :src="`/storage/${post.featured_image}`"
                                :alt="post.title"
                                class="w-full h-full object-cover"
                            />
                            <div
                                v-else
                                class="w-full h-full bg-gray-800 flex items-center justify-center"
                            >
                                <span class="text-gray-500">Pas d'image</span>
                            </div>
                        </div>

                        <!-- Contenu -->
                        <div class="p-6">
                            <div class="flex flex-wrap gap-2 mb-3">
                                <span
                                    v-for="tag in post.tags"
                                    :key="tag.id"
                                    class="text-xs bg-gaming-red/20 text-led-green px-2 py-1 rounded"
                                >
                                    {{ tag.name }}
                                </span>
                            </div>

                            <h2 class="text-xl font-play text-led-green mb-2">
                                {{ post.title }}
                            </h2>

                            <div class="text-white/60 text-sm mb-3">
                                <span>{{
                                    formatDate(
                                        post.published_at || post.created_at
                                    )
                                }}</span>
                                <span class="mx-2">•</span>
                                <span
                                    >{{ readingTime(post.content) }} min de
                                    lecture</span
                                >
                            </div>

                            <p
                                v-if="post.excerpt"
                                class="text-white/80 mb-4 line-clamp-3"
                            >
                                {{ post.excerpt }}
                            </p>
                            <p v-else class="text-white/80 mb-4 line-clamp-3">
                                {{
                                    post.content
                                        .replace(/<[^>]*>?/gm, "")
                                        .substring(0, 150)
                                }}...
                            </p>

                            <Link
                                :href="route('blog.show', post.slug)"
                                class="text-gaming-red hover:text-white transition"
                            >
                                Lire la suite
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Message si aucun article n'est disponible -->
                <div v-if="!posts.length" class="text-center py-12">
                    <p class="text-white/70 text-lg">
                        Aucun article n'est disponible pour le moment.
                    </p>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>
