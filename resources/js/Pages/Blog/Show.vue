<script setup>
import PublicLayout from "@/Layouts/PublicLayout.vue";
import { Link, useForm } from "@inertiajs/vue3";
import { onMounted, ref, computed } from "vue";

const props = defineProps({
    post: Object,
    relatedPosts: Array,
    comments: Array,
    errors: Object,
    flash: Object,
});

// État pour le formulaire de commentaire
const showCommentForm = ref(false);
// URL courante pour les boutons de partage (solution pour l'erreur)
const currentUrl = ref("");

// Configuration de l'URL côté client uniquement
onMounted(() => {
    currentUrl.value = window.location.href;

    // Injecter aussi le contenu HTML de manière sécurisée
    if (document.getElementById("blog-content")) {
        document.getElementById("blog-content").innerHTML = props.post.content;
    }
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

// Formulaire de commentaire
const form = useForm({
    name: "",
    email: "",
    content: "",
});

// Soumettre un commentaire
const submitComment = () => {
    form.post(route("blog.comment", props.post.slug), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            showCommentForm.value = false;
        },
    });
};
</script>

<template>
    <PublicLayout
        :title="post.title + ' | Blog'"
        :description="
            post.excerpt ||
            post.content.substring(0, 160).replace(/<[^>]*>/g, '')
        "
        :keywords="
            post.tags.map((tag) => tag.name).join(', ') +
            ', PC gaming, NextConfig'
        "
    >
        <!-- Hero Section adaptée à l'article -->
        <div class="blog-post-hero-section">
            <div class="hero-content z-10 relative text-center">
                <div class="max-w-4xl mx-auto">
                    <div
                        class="flex flex-wrap gap-2 justify-center mb-4 animate-fade-in"
                    >
                        <span
                            v-for="tag in post.tags"
                            :key="tag.id"
                            class="text-xs bg-gaming-red/20 text-led-green px-3 py-1 rounded-full shadow-glow-sm"
                        >
                            {{ tag.name }}
                        </span>
                    </div>
                    <h1
                        class="text-5xl md:text-6xl font-play text-gaming-red mb-6 hero-title"
                    >
                        {{ post.title }}
                    </h1>
                    <p
                        v-if="post.excerpt"
                        class="text-2xl text-white mb-8 max-w-3xl mx-auto font-medium text-shadow-lg"
                    >
                        {{ post.excerpt }}
                    </p>
                    <div
                        class="text-white/80 flex items-center justify-center space-x-4"
                    >
                        <span class="flex items-center">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5 mr-2 text-gaming-red"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                                />
                            </svg>
                            {{ formatDate(post.created_at) }}
                        </span>
                        <span class="flex items-center">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5 mr-2 text-gaming-red"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                />
                            </svg>
                            {{ readingTime(post.content) }} min de lecture
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <!-- Navigation et bouton retour -->
                <div class="mb-8 flex justify-between items-center">
                    <Link
                        :href="route('blog')"
                        class="inline-flex items-center text-led-green hover:text-gaming-red transition-all duration-300 group"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 mr-2 transform group-hover:-translate-x-1 transition-transform duration-300"
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

                    <!-- Boutons de partage -->
                    <div class="flex space-x-2">
                        <a
                            :href="`https://twitter.com/intent/tweet?url=${encodeURIComponent(
                                currentUrl
                            )}&text=${encodeURIComponent(post.title)}`"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="text-white hover:text-gaming-red transition-colors duration-300 p-2"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5"
                                fill="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723 10.054 10.054 0 01-3.127 1.184 4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"
                                />
                            </svg>
                        </a>
                        <a
                            :href="`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(
                                currentUrl
                            )}`"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="text-white hover:text-gaming-red transition-colors duration-300 p-2"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5"
                                fill="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"
                                />
                            </svg>
                        </a>
                        <a
                            :href="`https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(
                                currentUrl
                            )}`"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="text-white hover:text-gaming-red transition-colors duration-300 p-2"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5"
                                fill="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"
                                />
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Image principale si présente -->
                <div v-if="post.featured_image" class="mb-8 animate-fade-in">
                    <div
                        class="rounded-lg overflow-hidden border border-gaming-red shadow-glow"
                    >
                        <img
                            :src="`/storage/${post.featured_image}`"
                            :alt="post.title"
                            class="w-full h-auto"
                        />
                    </div>
                </div>

                <!-- Contenu principal de l'article -->
                <div
                    class="bg-deep-black border border-gaming-red rounded-lg p-8 mb-12 shadow-glow-sm hover-card animate-fade-in"
                >
                    <div
                        id="blog-content"
                        class="prose prose-invert max-w-none prose-headings:font-play prose-headings:text-led-green prose-a:text-gaming-red hover:prose-a:text-white prose-pre:bg-deep-black/50 prose-pre:border prose-pre:border-gaming-red/30 prose-img:rounded-lg"
                    ></div>
                </div>

                <!-- Section commentaires avec style amélioré -->
                <div
                    class="bg-deep-black border border-gaming-red rounded-lg p-8 mb-12 shadow-glow-sm hover-card animate-fade-in"
                >
                    <div class="flex justify-between items-center mb-6">
                        <h3
                            class="text-2xl font-play text-led-green section-title"
                        >
                            Commentaires ({{ comments.length }})
                        </h3>

                        <!-- Bouton pour afficher le formulaire de commentaire -->
                        <button
                            @click="showCommentForm = !showCommentForm"
                            class="main-cta-button inline-flex items-center px-4 py-2 border border-transparent text-base font-medium rounded-md text-white"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5 mr-2"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    v-if="showCommentForm"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"
                                />
                                <path
                                    v-else
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 4v16m8-8H4"
                                />
                            </svg>
                            {{
                                showCommentForm
                                    ? "Annuler"
                                    : "Ajouter un commentaire"
                            }}
                        </button>
                    </div>

                    <!-- Message flash de succès -->
                    <div
                        v-if="$page.props.flash.success"
                        class="bg-led-green/20 border border-led-green/50 text-white p-4 rounded mb-6 animate-fade-in"
                    >
                        <div class="flex items-start">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5 mr-2 text-led-green flex-shrink-0 mt-0.5"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                />
                            </svg>
                            <span>{{ $page.props.flash.success }}</span>
                        </div>
                    </div>

                    <!-- Formulaire de commentaire amélioré -->
                    <div
                        v-if="showCommentForm"
                        class="bg-deep-black/50 border border-gaming-red/50 rounded-lg p-6 mb-8 animate-fade-in"
                    >
                        <form @submit.prevent="submitComment" class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-white mb-2"
                                        >Nom *</label
                                    >
                                    <input
                                        v-model="form.name"
                                        type="text"
                                        class="w-full bg-deep-black border border-gaming-red rounded-md px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-led-green transition-all duration-300"
                                        required
                                    />
                                    <p
                                        v-if="form.errors.name"
                                        class="text-gaming-red text-sm mt-1"
                                    >
                                        {{ form.errors.name }}
                                    </p>
                                </div>
                                <div>
                                    <label class="block text-white mb-2"
                                        >Email *</label
                                    >
                                    <input
                                        v-model="form.email"
                                        type="email"
                                        class="w-full bg-deep-black border border-gaming-red rounded-md px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-led-green transition-all duration-300"
                                        required
                                    />
                                    <p
                                        v-if="form.errors.email"
                                        class="text-gaming-red text-sm mt-1"
                                    >
                                        {{ form.errors.email }}
                                    </p>
                                </div>
                            </div>
                            <div>
                                <label class="block text-white mb-2"
                                    >Commentaire *</label
                                >
                                <textarea
                                    v-model="form.content"
                                    rows="4"
                                    class="w-full bg-deep-black border border-gaming-red rounded-md px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-led-green transition-all duration-300"
                                    required
                                ></textarea>
                                <p
                                    v-if="form.errors.content"
                                    class="text-gaming-red text-sm mt-1"
                                >
                                    {{ form.errors.content }}
                                </p>
                            </div>
                            <div class="text-white/60 text-sm mb-2">
                                <div class="flex items-start">
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5 mr-2 text-gaming-red flex-shrink-0 mt-0.5"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                        />
                                    </svg>
                                    <span
                                        >Tous les commentaires sont soumis à
                                        modération avant publication.</span
                                    >
                                </div>
                            </div>
                            <div>
                                <button
                                    type="submit"
                                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md text-white bg-led-green hover:bg-opacity-90 transition duration-300 shadow-glow-sm"
                                    :disabled="form.processing"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5 mr-2"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        v-if="!form.processing"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"
                                        />
                                    </svg>
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="animate-spin h-5 w-5 mr-2"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        v-else
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"
                                        />
                                    </svg>
                                    {{
                                        form.processing
                                            ? "Envoi en cours..."
                                            : "Envoyer"
                                    }}
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Liste des commentaires améliorée -->
                    <div v-if="comments.length" class="space-y-4">
                        <div
                            v-for="comment in comments"
                            :key="comment.id"
                            class="bg-deep-black/30 border border-gaming-red/30 rounded-lg p-5 transition-all duration-300 hover:border-gaming-red/60 hover:shadow-glow-sm"
                        >
                            <div class="flex justify-between mb-3">
                                <h4
                                    class="text-led-green font-medium flex items-center"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-4 w-4 mr-2"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                        />
                                    </svg>
                                    {{ comment.name }}
                                </h4>
                                <span
                                    class="text-white/60 text-sm flex items-center"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-4 w-4 mr-1"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                                        />
                                    </svg>
                                    {{ formatDate(comment.created_at) }}
                                </span>
                            </div>
                            <p class="text-white/90">{{ comment.content }}</p>
                        </div>
                    </div>

                    <!-- Message si aucun commentaire -->
                    <div
                        v-else
                        class="text-white/60 text-center py-6 border border-dashed border-gaming-red/30 rounded-lg"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-10 w-10 mx-auto mb-3 text-gaming-red/50"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"
                            />
                        </svg>
                        <p class="text-lg">Aucun commentaire pour le moment.</p>
                        <p class="mt-2">
                            Soyez le premier à partager votre avis !
                        </p>
                    </div>
                </div>

                <!-- Articles connexes améliorés -->
                <div v-if="relatedPosts.length" class="mb-12 animate-fade-in">
                    <h3
                        class="text-2xl font-play text-gaming-red mb-8 section-title text-center"
                    >
                        Articles connexes
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div
                            v-for="related in relatedPosts"
                            :key="related.id"
                            class="bg-deep-black border border-gaming-red rounded-lg overflow-hidden service-card group"
                        >
                            <!-- Image avec effet hover -->
                            <div
                                class="h-40 overflow-hidden relative card-image-container"
                            >
                                <img
                                    v-if="related.featured_image"
                                    :src="`/storage/${related.featured_image}`"
                                    :alt="related.title"
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
                                        :href="route('blog.show', related.slug)"
                                        class="px-3 py-1 bg-gaming-red text-white rounded-md transform translate-y-4 opacity-0 transition-all duration-300 view-details-btn text-sm"
                                    >
                                        Lire l'article
                                    </Link>
                                </div>
                            </div>

                            <!-- Contenu -->
                            <div class="p-4">
                                <h4
                                    class="text-lg font-play text-led-green mb-2 line-clamp-1 group-hover:text-gaming-red transition-colors duration-300"
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
                                    class="inline-flex items-center text-sm text-gaming-red hover:text-white transition group"
                                >
                                    <span class="mr-1">Lire l'article</span>
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
                </div>
            </div>
        </div>
    </PublicLayout>
</template>

<style>
/* Style pour la hero section de l'article */
.blog-post-hero-section {
    position: relative;
    width: 100%;
    min-height: 60vh;
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

/* Animation pour le titre */
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

/* Section title avec décoration */
.section-title {
    position: relative;
    display: inline-block;
    padding-bottom: 10px;
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

/* Effet glow pour les éléments importants */
.shadow-glow {
    box-shadow: 0 0 20px rgba(236, 64, 122, 0.4);
}

.shadow-glow-sm {
    box-shadow: 0 0 8px rgba(236, 64, 122, 0.3);
    transition: box-shadow 0.3s ease;
}

/* Effet glow pour le bouton principal */
.main-cta-button {
    position: relative;
    background: linear-gradient(45deg, #ec407a, #d81b60);
    box-shadow: 0 0 15px rgba(236, 64, 122, 0.5);
    transform: translateY(0);
    transition: all 0.3s ease;
}

.main-cta-button:hover {
    transform: translateY(-3px);
    box-shadow: 0 0 25px rgba(236, 64, 122, 0.7);
}

.main-cta-button:active {
    transform: translateY(0);
}

/* Animation pour les cartes d'article */
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
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2), 0 0 15px rgba(236, 64, 122, 0.3);
    border-color: rgba(236, 64, 122, 0.8);
}

.service-card:hover::before {
    opacity: 1;
}

.card-image-container:hover img {
    transform: scale(1.05);
}

.service-card:hover .view-details-btn {
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

/* Animation pour l'apparition des éléments */
.animate-fade-in {
    animation: fadeIn 0.6s ease-out;
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

/* Styles spécifiques pour le contenu d'article */
.prose img {
    border-radius: 0.5rem;
    max-width: 100%;
    height: auto;
    transition: transform 0.3s ease;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.6);
}

.prose img:hover {
    transform: scale(1.02);
}

.prose pre {
    background-color: #1a1a1a;
    border-radius: 0.5rem;
    padding: 1rem;
    overflow-x: auto;
    border: 1px solid rgba(236, 64, 122, 0.3);
}

.prose blockquote {
    border-left: 4px solid #ec407a;
    padding-left: 1rem;
    font-style: italic;
    color: rgba(255, 255, 255, 0.8);
    background-color: rgba(0, 0, 0, 0.2);
    padding: 1rem;
    border-radius: 0 0.5rem 0.5rem 0;
}

.prose h2,
.prose h3,
.prose h4 {
    position: relative;
    padding-bottom: 0.5rem;
}
</style>
