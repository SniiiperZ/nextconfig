<script setup>
import PublicLayout from "@/Layouts/PublicLayout.vue";
import { Link } from "@inertiajs/vue3";
import { computed } from "vue";

const props = defineProps({
    posts: Array,
    categories: Array,
    tags: Array,
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
        <!-- Hero Section pour la page Blog -->
        <div class="blog-hero-section">
            <div class="hero-content z-10 relative text-center">
                <h1
                    class="text-6xl md:text-7xl font-play text-gaming-red mb-6 hero-title"
                >
                    Blog Tech
                </h1>
                <p
                    class="text-2xl text-white mb-12 max-w-3xl mx-auto font-medium text-shadow-lg"
                >
                    Actualités, guides et conseils sur le monde du PC Gaming
                </p>
            </div>
        </div>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Featured Posts -->
                <div
                    v-if="
                        posts.length && posts.some((post) => post.is_featured)
                    "
                    class="mb-16"
                >
                    <div class="flex justify-center mb-10">
                        <h2
                            class="text-4xl font-play text-gaming-red section-title"
                        >
                            Articles à la une
                        </h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div
                            v-for="post in posts
                                .filter((p) => p.is_featured)
                                .slice(0, 2)"
                            :key="post.id"
                            class="featured-post bg-deep-black border border-gaming-red rounded-lg overflow-hidden hover-card"
                        >
                            <!-- Image -->
                            <div
                                class="h-64 overflow-hidden relative card-image-container"
                            >
                                <img
                                    v-if="post.featured_image"
                                    :src="`/storage/${post.featured_image}`"
                                    :alt="post.title"
                                    class="w-full h-full object-cover transition-transform duration-500"
                                />
                                <div
                                    v-else
                                    class="w-full h-full bg-gray-800 flex items-center justify-center"
                                >
                                    <span class="text-gray-500"
                                        >Pas d'image</span
                                    >
                                </div>

                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-deep-black to-transparent opacity-60"
                                ></div>
                            </div>

                            <!-- Content -->
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

                                <h2
                                    class="text-2xl font-play text-led-green mb-2 hover-glow"
                                >
                                    {{ post.title }}
                                </h2>

                                <div class="text-white/60 text-sm mb-3">
                                    <span>{{
                                        formatDate(post.created_at)
                                    }}</span>
                                    <span class="mx-2">•</span>
                                    <span
                                        >{{ readingTime(post.content) }} min de
                                        lecture</span
                                    >
                                </div>

                                <p class="text-white/80 mb-4 line-clamp-3">
                                    {{
                                        post.excerpt ||
                                        post.content
                                            .replace(/<[^>]*>?/gm, "")
                                            .substring(0, 150) + "..."
                                    }}
                                </p>

                                <Link
                                    :href="route('blog.show', post.slug)"
                                    class="inline-flex items-center text-gaming-red hover:text-white transition group"
                                >
                                    <span class="mr-2">Lire l'article</span>
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5 transform group-hover:translate-x-1 transition-transform duration-200"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M14 5l7 7m0 0l-7 7m7-7H3"
                                        />
                                    </svg>
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Latest Posts -->
                <div class="mb-16">
                    <div class="flex justify-center mb-10">
                        <h2
                            class="text-4xl font-play text-gaming-red section-title"
                        >
                            Derniers articles
                        </h2>
                    </div>

                    <div
                        v-if="posts.length"
                        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8"
                    >
                        <div
                            v-for="post in posts"
                            :key="post.id"
                            class="bg-deep-black border border-gaming-red rounded-lg overflow-hidden blog-card service-card group"
                        >
                            <!-- Image -->
                            <div
                                class="h-48 overflow-hidden relative card-image-container"
                            >
                                <img
                                    v-if="post.featured_image"
                                    :src="`/storage/${post.featured_image}`"
                                    :alt="post.title"
                                    class="w-full h-full object-cover transition-transform duration-500"
                                />
                                <div
                                    v-else
                                    class="w-full h-full bg-gray-800 flex items-center justify-center"
                                >
                                    <span class="text-gray-500"
                                        >Pas d'image</span
                                    >
                                </div>

                                <!-- Overlay avec effet de brillance -->
                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-deep-black to-transparent opacity-0 hover:opacity-70 transition-opacity duration-300 flex items-end justify-center p-4"
                                >
                                    <Link
                                        :href="route('blog.show', post.slug)"
                                        class="px-4 py-2 bg-gaming-red text-white rounded-md transform translate-y-4 opacity-0 transition-all duration-300 view-details-btn"
                                    >
                                        Lire l'article
                                    </Link>
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

                                <h2
                                    class="text-xl font-play text-led-green mb-2 group-hover:text-gaming-red transition-colors duration-300"
                                >
                                    {{ post.title }}
                                </h2>

                                <div class="text-white/60 text-sm mb-3">
                                    <span>{{
                                        formatDate(post.created_at)
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
                                <p
                                    v-else
                                    class="text-white/80 mb-4 line-clamp-3"
                                >
                                    {{
                                        post.content
                                            .replace(/<[^>]*>?/gm, "")
                                            .substring(0, 150)
                                    }}...
                                </p>

                                <Link
                                    :href="route('blog.show', post.slug)"
                                    class="inline-flex items-center text-gaming-red hover:text-white transition group"
                                >
                                    <span class="mr-1">Lire la suite</span>
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-4 w-4 transform group-hover:translate-x-1 transition-transform duration-200"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M14 5l7 7m0 0l-7 7m7-7H3"
                                        />
                                    </svg>
                                </Link>
                            </div>
                        </div>
                    </div>

                    <!-- Message si aucun article n'est disponible -->
                    <div v-if="!posts.length" class="text-center py-12">
                        <div
                            class="inline-block p-8 border border-gaming-red/50 rounded-lg bg-deep-black/50 shadow-glow"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-12 w-12 text-gaming-red/70 mx-auto mb-4"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
                                />
                            </svg>
                            <p class="text-white/70 text-lg">
                                Aucun article n'est disponible pour le moment.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>

<style scoped>
/* Style pour la hero section */
.blog-hero-section {
    position: relative;
    width: 100%;
    min-height: 50vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background-image: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.85)),
        url("/images/background.jpg");
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    padding: 2rem;
    margin-bottom: 2rem;
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

/* Styles pour les titres de section */
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

/* Animation pour les cartes d'article */
.blog-card {
    transform: translateY(0);
    transition: all 0.3s ease;
}

.blog-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2), 0 0 15px rgba(236, 64, 122, 0.3);
    border-color: rgba(236, 64, 122, 0.8);
}

/* Animation pour les cartes de service */
.service-card {
    position: relative;
    overflow: hidden;
    background: rgba(0, 0, 0, 0.2);
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

.service-card:hover::before {
    opacity: 1;
}

.card-image-container:hover img {
    transform: scale(1.05);
}

.blog-card:hover .view-details-btn {
    transform: translateY(0);
    opacity: 1;
}

/* Cartes avec hover effect */
.hover-card {
    transition: all 0.3s ease;
}

.hover-card:hover {
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3), 0 0 15px rgba(236, 64, 122, 0.3);
    transform: translateY(-5px);
}

/* Effet de glow pour les titres des articles mis en avant */
.hover-glow {
    transition: text-shadow 0.3s ease;
}

.hover-glow:hover {
    text-shadow: 0 0 10px rgba(236, 64, 122, 0.5);
}

.shadow-glow {
    box-shadow: 0 0 20px rgba(236, 64, 122, 0.4);
}

/* Animation pour l'apparition des éléments */
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
</style>
