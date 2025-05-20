<script setup>
import PublicLayout from "@/Layouts/PublicLayout.vue";
import HeroSection from "@/Components/HeroSection.vue";
import HeroTitle from "@/Components/HeroTitle.vue";
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
    <PublicLayout
        title="Blog"
        description="Découvrez nos articles sur le PC gaming, guides d'assemblage, tests de composants et optimisations pour améliorer votre expérience de jeu."
        keywords="blog PC gaming, tests composants PC, guides assemblage, technologies gaming, tutoriels PC, NextConfig blog"
    >
        <!-- Hero Section pour la page Blog avec le composant HeroSection -->
        <HeroSection
            title="Blog Tech"
            subtitle="Actualités, guides et conseils sur le monde du PC Gaming"
            backgroundImage="/images/background.jpg"
            height="min-h-50vh"
        />

        <div class="py-8 sm:py-10 md:py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Featured Posts -->
                <div
                    v-if="
                        posts.length && posts.some((post) => post.is_featured)
                    "
                    class="mb-8 sm:mb-12 md:mb-16"
                >
                    <HeroTitle
                        title="Articles à la une"
                        :centered="true"
                        marginBottom="mb-6 sm:mb-8 md:mb-10"
                    />

                    <div
                        class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 md:gap-8"
                    >
                        <div
                            v-for="post in posts
                                .filter((p) => p.is_featured)
                                .slice(0, 2)"
                            :key="post.id"
                            class="featured-post bg-deep-black border border-gaming-red rounded-lg overflow-hidden hover-card animate-fade-in"
                        >
                            <!-- Image -->
                            <div
                                class="h-48 sm:h-56 md:h-64 overflow-hidden relative card-image-container"
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
                            <div class="p-4 sm:p-5 md:p-6">
                                <div
                                    class="flex flex-wrap gap-1.5 sm:gap-2 mb-2 sm:mb-3"
                                >
                                    <span
                                        v-for="tag in post.tags"
                                        :key="tag.id"
                                        class="text-xs bg-gaming-red/20 text-led-green px-1.5 sm:px-2 py-0.5 sm:py-1 rounded"
                                    >
                                        {{ tag.name }}
                                    </span>
                                </div>

                                <h2
                                    class="text-xl sm:text-2xl font-play text-led-green mb-1.5 sm:mb-2 hover-glow line-clamp-2"
                                >
                                    {{ post.title }}
                                </h2>

                                <div
                                    class="text-white/60 text-xs sm:text-sm mb-2 sm:mb-3"
                                >
                                    <span>{{
                                        formatDate(post.created_at)
                                    }}</span>
                                    <span class="mx-1 sm:mx-2">•</span>
                                    <span
                                        >{{ readingTime(post.content) }} min de
                                        lecture</span
                                    >
                                </div>

                                <p
                                    class="text-white/80 text-sm sm:text-base mb-3 sm:mb-4 line-clamp-2 sm:line-clamp-3"
                                >
                                    {{
                                        post.excerpt ||
                                        post.content
                                            .replace(/<[^>]*>?/gm, "")
                                            .substring(0, 150) + "..."
                                    }}
                                </p>

                                <Link
                                    :href="route('blog.show', post.slug)"
                                    class="inline-flex items-center text-sm sm:text-base text-gaming-red hover:text-white transition group"
                                >
                                    <span class="mr-1.5 sm:mr-2"
                                        >Lire l'article</span
                                    >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-4 w-4 sm:h-5 sm:w-5 transform group-hover:translate-x-1 transition-transform duration-200"
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
                <div class="mb-8 sm:mb-12 md:mb-16">
                    <HeroTitle
                        title="Derniers articles"
                        :centered="true"
                        marginBottom="mb-6 sm:mb-8 md:mb-10"
                    />

                    <div
                        v-if="posts.length"
                        class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 md:gap-8"
                    >
                        <div
                            v-for="post in posts"
                            :key="post.id"
                            class="bg-deep-black border border-gaming-red rounded-lg overflow-hidden blog-card service-card group"
                        >
                            <!-- Image -->
                            <div
                                class="h-40 sm:h-44 md:h-48 overflow-hidden relative card-image-container"
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
                                    class="absolute inset-0 bg-gradient-to-t from-deep-black to-transparent opacity-0 hover:opacity-70 transition-opacity duration-300 flex items-end justify-center p-3 sm:p-4"
                                >
                                    <Link
                                        :href="route('blog.show', post.slug)"
                                        class="px-2.5 py-1.5 sm:px-4 sm:py-2 bg-gaming-red text-white text-xs sm:text-sm rounded-md transform translate-y-4 opacity-0 transition-all duration-300 view-details-btn"
                                    >
                                        Lire l'article
                                    </Link>
                                </div>
                            </div>

                            <!-- Contenu -->
                            <div class="p-4 sm:p-5 md:p-6">
                                <div
                                    class="flex flex-wrap gap-1.5 sm:gap-2 mb-2 sm:mb-3"
                                >
                                    <span
                                        v-for="tag in post.tags"
                                        :key="tag.id"
                                        class="text-xs bg-gaming-red/20 text-led-green px-1.5 sm:px-2 py-0.5 sm:py-1 rounded"
                                    >
                                        {{ tag.name }}
                                    </span>
                                </div>

                                <h2
                                    class="text-lg sm:text-xl font-play text-led-green mb-1.5 sm:mb-2 group-hover:text-gaming-red transition-colors duration-300 line-clamp-2"
                                >
                                    {{ post.title }}
                                </h2>

                                <div
                                    class="text-white/60 text-xs sm:text-sm mb-2 sm:mb-3"
                                >
                                    <span>{{
                                        formatDate(post.created_at)
                                    }}</span>
                                    <span class="mx-1 sm:mx-2">•</span>
                                    <span
                                        >{{ readingTime(post.content) }} min de
                                        lecture</span
                                    >
                                </div>

                                <p
                                    class="text-white/80 text-xs sm:text-sm mb-3 sm:mb-4 line-clamp-2 sm:line-clamp-3"
                                >
                                    {{
                                        post.excerpt ||
                                        post.content
                                            .replace(/<[^>]*>?/gm, "")
                                            .substring(0, 150) + "..."
                                    }}
                                </p>

                                <Link
                                    :href="route('blog.show', post.slug)"
                                    class="inline-flex items-center text-xs sm:text-sm text-gaming-red hover:text-white transition group"
                                >
                                    <span class="mr-1 sm:mr-1.5"
                                        >Lire la suite</span
                                    >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-3.5 w-3.5 sm:h-4 sm:w-4 transform group-hover:translate-x-1 transition-transform duration-200"
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
                    <div
                        v-if="!posts.length"
                        class="text-center py-8 sm:py-10 md:py-12"
                    >
                        <div
                            class="inline-block p-5 sm:p-6 md:p-8 border border-gaming-red/50 rounded-lg bg-deep-black/50 shadow-glow"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-10 w-10 sm:h-12 sm:w-12 text-gaming-red/70 mx-auto mb-3 sm:mb-4"
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
                            <p
                                class="text-white/70 text-sm sm:text-base md:text-lg"
                            >
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
/* Cartes avec hover effect */
.blog-card {
    transform: translateY(0);
    transition: all 0.3s ease;
}

.blog-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2), 0 0 15px rgba(236, 64, 122, 0.3);
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

/* Animation d'entrée pour les blocs de contenu */
.animate-fade-in {
    animation: fadeIn 0.6s ease-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Ajustements responsive pour les appareils mobiles */
@media (max-width: 640px) {
    .blog-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2),
            0 0 10px rgba(236, 64, 122, 0.3);
    }

    .hover-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2),
            0 0 10px rgba(236, 64, 122, 0.3);
    }

    .hover-glow:hover {
        text-shadow: 0 0 5px rgba(236, 64, 122, 0.4);
    }

    .shadow-glow {
        box-shadow: 0 0 10px rgba(236, 64, 122, 0.3);
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(6px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
}
</style>
