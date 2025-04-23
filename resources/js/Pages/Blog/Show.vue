<script setup>
import PublicLayout from "@/Layouts/PublicLayout.vue";
import { Link } from "@inertiajs/vue3";
import { onMounted } from "vue";

const props = defineProps({
    post: Object,
    relatedPosts: Array,
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

// Appliquer le contenu HTML de manière sécurisée
onMounted(() => {
    if (document.getElementById("blog-content")) {
        document.getElementById("blog-content").innerHTML = props.post.content;
    }
});
</script>

<template>
    <PublicLayout :title="post.title">
        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <!-- Bouton retour -->
                <Link
                    :href="route('blog')"
                    class="inline-flex items-center text-led-green mb-6 hover:text-gaming-red transition"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5 mr-2"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"
                        />
                    </svg>
                    Retour aux articles
                </Link>

                <!-- En-tête de l'article -->
                <div
                    class="bg-deep-black border border-gaming-red rounded-lg overflow-hidden mb-8"
                >
                    <!-- Image principale -->
                    <div
                        v-if="post.featured_image"
                        class="w-full h-80 overflow-hidden"
                    >
                        <img
                            :src="`/storage/${post.featured_image}`"
                            :alt="post.title"
                            class="w-full h-full object-cover"
                        />
                    </div>

                    <!-- Contenu de l'en-tête -->
                    <div class="p-8">
                        <div class="flex flex-wrap gap-2 mb-4">
                            <span
                                v-for="tag in post.tags"
                                :key="tag.id"
                                class="text-xs bg-gaming-red/20 text-led-green px-2 py-1 rounded"
                            >
                                {{ tag.name }}
                            </span>
                        </div>

                        <h1
                            class="text-3xl md:text-4xl font-play text-led-green mb-4"
                        >
                            {{ post.title }}
                        </h1>

                        <div class="text-white/60 text-sm mb-4">
                            <span>{{ formatDate(post.created_at) }}</span>
                            <span class="mx-2">•</span>
                            <span
                                >{{ readingTime(post.content) }} min de
                                lecture</span
                            >
                        </div>

                        <p
                            v-if="post.excerpt"
                            class="text-white/80 text-lg italic"
                        >
                            {{ post.excerpt }}
                        </p>
                    </div>
                </div>

                <!-- Contenu principal de l'article -->
                <div
                    class="bg-deep-black border border-gaming-red rounded-lg p-8 mb-12"
                >
                    <div
                        id="blog-content"
                        class="prose prose-invert max-w-none prose-headings:font-play prose-headings:text-led-green prose-a:text-gaming-red hover:prose-a:text-white"
                    ></div>
                </div>

                <!-- Articles connexes -->
                <div v-if="relatedPosts.length" class="mt-12">
                    <h3 class="text-2xl font-play text-led-green mb-6">
                        Articles connexes
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div
                            v-for="related in relatedPosts"
                            :key="related.id"
                            class="bg-deep-black border border-gaming-red rounded-lg overflow-hidden transition-transform hover:scale-105"
                        >
                            <!-- Image -->
                            <div class="h-40 overflow-hidden">
                                <img
                                    v-if="related.featured_image"
                                    :src="`/storage/${related.featured_image}`"
                                    :alt="related.title"
                                    class="w-full h-full object-cover"
                                />
                                <div
                                    v-else
                                    class="w-full h-full bg-gray-800 flex items-center justify-center"
                                >
                                    <span class="text-gray-500"
                                        >Pas d'image</span
                                    >
                                </div>
                            </div>

                            <!-- Contenu -->
                            <div class="p-4">
                                <h4
                                    class="text-lg font-play text-led-green mb-2 line-clamp-1"
                                >
                                    {{ related.title }}
                                </h4>

                                <p
                                    class="text-white/80 text-sm mb-3 line-clamp-2"
                                >
                                    {{
                                        related.excerpt ||
                                        related.content
                                            .replace(/<[^>]*>?/gm, "")
                                            .substring(0, 100)
                                    }}...
                                </p>

                                <Link
                                    :href="route('blog.show', related.slug)"
                                    class="text-sm text-gaming-red hover:text-white transition"
                                >
                                    Lire l'article
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>

<style>
/* Ajoutez des styles spécifiques pour le contenu du blog si nécessaire */
.prose img {
    border-radius: 0.5rem;
    max-width: 100%;
    height: auto;
}

.prose pre {
    background-color: #1a1a1a;
    border-radius: 0.5rem;
    padding: 1rem;
    overflow-x: auto;
}

.prose blockquote {
    border-left: 4px solid #ef4444;
    padding-left: 1rem;
    font-style: italic;
}
</style>
