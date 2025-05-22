<script setup>
import { ref, computed } from "vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { useForm, router } from "@inertiajs/vue3";
import {
    TransitionRoot,
    TransitionChild,
    Dialog,
    DialogPanel,
} from "@headlessui/vue";
import {
    ExclamationTriangleIcon,
    MagnifyingGlassIcon,
    ArrowUpIcon,
    ArrowDownIcon,
} from "@heroicons/vue/24/outline";

const props = defineProps({
    reviews: Array,
});

const form = useForm({
    name: "",
    email: "",
    rating: 5,
    comment: "",
    is_approved: true,
    is_featured: false,
    order: 0,
});

const editing = ref(null);
const showDeleteModal = ref(false);
const reviewToDelete = ref(null);
const searchQuery = ref("");
const sortBy = ref("created_at");
const sortDirection = ref("desc");
const updateMessage = ref("");
const updateStatus = ref(null); // null, 'success' ou 'error'

// Filtrage et tri des avis
const filteredReviews = computed(() => {
    let filtered = [...props.reviews];

    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        filtered = filtered.filter(
            (review) =>
                review.name.toLowerCase().includes(query) ||
                (review.email && review.email.toLowerCase().includes(query)) ||
                review.comment.toLowerCase().includes(query)
        );
    }

    filtered.sort((a, b) => {
        let comparison = 0;
        let aValue = a[sortBy.value];
        let bValue = b[sortBy.value];

        // Gérer les dates
        if (sortBy.value === "created_at" || sortBy.value === "updated_at") {
            aValue = aValue ? new Date(aValue) : new Date(0);
            bValue = bValue ? new Date(bValue) : new Date(0);
        }

        if (aValue > bValue) {
            comparison = 1;
        } else if (aValue < bValue) {
            comparison = -1;
        }
        return sortDirection.value === "asc" ? comparison : -comparison;
    });

    return filtered;
});

const toggleSort = (column) => {
    if (sortBy.value === column) {
        sortDirection.value = sortDirection.value === "asc" ? "desc" : "asc";
    } else {
        sortBy.value = column;
        sortDirection.value = "desc";
    }
};

const submit = () => {
    if (editing.value) {
        form.put(route("admin.reviews.update", editing.value.id), {
            preserveScroll: true,
            onSuccess: () => {
                editing.value = null;
                form.reset();
                updateStatus.value = "success";
                updateMessage.value = "Avis mis à jour avec succès !";
                setTimeout(() => {
                    updateStatus.value = null;
                    updateMessage.value = "";
                }, 3000);
            },
        });
    } else {
        form.post(route("admin.reviews.store"), {
            preserveScroll: true,
            onSuccess: () => {
                form.reset();
                updateStatus.value = "success";
                updateMessage.value = "Avis ajouté avec succès !";
                setTimeout(() => {
                    updateStatus.value = null;
                    updateMessage.value = "";
                }, 3000);
            },
        });
    }
};

const editReview = (review) => {
    editing.value = review;
    form.name = review.name;
    form.email = review.email || "";
    form.rating = review.rating;
    form.comment = review.comment;
    form.is_approved = review.is_approved;
    form.is_featured = review.is_featured;
    form.order = review.order;

    // Scroll to form
    setTimeout(() => {
        document
            .querySelector("#review-form")
            .scrollIntoView({ behavior: "smooth" });
    }, 100);
};

const cancelEdit = () => {
    editing.value = null;
    form.reset();
};

const approveReview = (review) => {
    useForm({
        is_approved: true,
    }).patch(route("admin.reviews.approval", review.id), {
        preserveScroll: true,
        onSuccess: () => {
            updateStatus.value = "success";
            updateMessage.value = "Avis approuvé avec succès !";
            setTimeout(() => {
                updateStatus.value = null;
                updateMessage.value = "";
            }, 3000);
        },
    });
};

const rejectReview = (review) => {
    useForm({
        is_approved: false,
    }).patch(route("admin.reviews.approval", review.id), {
        preserveScroll: true,
        onSuccess: () => {
            updateStatus.value = "success";
            updateMessage.value = "Avis suspendu avec succès !";
            setTimeout(() => {
                updateStatus.value = null;
                updateMessage.value = "";
            }, 3000);
        },
    });
};

const confirmDelete = (review) => {
    reviewToDelete.value = review;
    showDeleteModal.value = true;
};

const deleteReview = () => {
    router.delete(route("admin.reviews.destroy", reviewToDelete.value.id), {
        preserveScroll: true,
        onFinish: () => {
            showDeleteModal.value = false;
            reviewToDelete.value = null;
            updateStatus.value = "success";
            updateMessage.value = "Avis supprimé avec succès !";
            setTimeout(() => {
                updateStatus.value = null;
                updateMessage.value = "";
            }, 3000);
        },
    });
};

// Fonction pour rendre les étoiles de notation
const renderStars = (rating) => {
    let stars = [];
    for (let i = 1; i <= 5; i++) {
        stars.push(i <= rating);
    }
    return stars;
};

// Formater la date pour l'affichage
const formatDate = (dateString) => {
    if (!dateString) return "";
    const date = new Date(dateString);
    return date.toLocaleDateString("fr-FR", {
        day: "numeric",
        month: "long",
        year: "numeric",
    });
};
</script>

<template>
    <AdminLayout title="Gestion des Avis">
        <template #header>
            <div
                class="flex flex-col sm:flex-row justify-between items-center gap-4"
            >
                <h2 class="font-semibold text-xl text-white leading-tight">
                    Gestion des Avis Clients
                </h2>
                <div class="relative w-full sm:w-auto">
                    <div
                        class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                    >
                        <MagnifyingGlassIcon
                            class="h-5 w-5 text-gray-400"
                            aria-hidden="true"
                        />
                    </div>
                    <input
                        v-model="searchQuery"
                        type="text"
                        placeholder="Rechercher..."
                        class="block w-full pl-10 pr-3 py-2 border border-gaming-red rounded-md bg-deep-black/50 text-white focus:outline-none focus:ring-2 focus:ring-led-green"
                    />
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Formulaire d'ajout/modification d'avis -->
                <div
                    id="review-form"
                    class="bg-deep-black p-4 sm:p-6 rounded-lg shadow mb-8 border border-gaming-red/30 hover:border-gaming-red/70 transition-all duration-300"
                >
                    <h3
                        class="text-xl font-play font-semibold text-led-green mb-4"
                    >
                        {{
                            editing
                                ? "Modifier un avis"
                                : "Ajouter un nouvel avis"
                        }}
                    </h3>

                    <form @submit.prevent="submit" class="space-y-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-white mb-2 font-medium"
                                    >Nom</label
                                >
                                <input
                                    v-model="form.name"
                                    type="text"
                                    class="w-full bg-deep-black border border-gaming-red rounded-md px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-led-green transition-all duration-200"
                                    :class="{
                                        'border-red-500': form.errors.name,
                                    }"
                                />
                                <p
                                    v-if="form.errors.name"
                                    class="text-red-500 mt-1 text-sm"
                                >
                                    {{ form.errors.name }}
                                </p>
                            </div>

                            <div>
                                <label
                                    class="block text-white mb-2 font-medium"
                                >
                                    Email
                                    <span class="text-xs text-white/60"
                                        >(optionnel)</span
                                    >
                                </label>
                                <input
                                    v-model="form.email"
                                    type="email"
                                    class="w-full bg-deep-black border border-gaming-red rounded-md px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-led-green transition-all duration-200"
                                    :class="{
                                        'border-red-500': form.errors.email,
                                    }"
                                />
                                <p
                                    v-if="form.errors.email"
                                    class="text-red-500 mt-1 text-sm"
                                >
                                    {{ form.errors.email }}
                                </p>
                            </div>
                        </div>

                        <div>
                            <label class="block text-white mb-2 font-medium"
                                >Note</label
                            >
                            <div class="flex space-x-3">
                                <button
                                    v-for="star in 5"
                                    :key="star"
                                    type="button"
                                    @click="form.rating = star"
                                    class="text-3xl focus:outline-none transition-colors duration-200"
                                    :class="
                                        star <= form.rating
                                            ? 'text-yellow-400 hover:text-yellow-300'
                                            : 'text-gray-600 hover:text-gray-400'
                                    "
                                >
                                    ★
                                </button>
                            </div>
                            <p
                                v-if="form.errors.rating"
                                class="text-red-500 mt-1 text-sm"
                            >
                                {{ form.errors.rating }}
                            </p>
                        </div>

                        <div>
                            <label class="block text-white mb-2 font-medium">
                                Commentaire
                                <span class="text-xs text-white/60"
                                    >(max. 150 caractères)</span
                                >
                            </label>
                            <textarea
                                v-model="form.comment"
                                rows="4"
                                maxlength="150"
                                class="w-full bg-deep-black border border-gaming-red rounded-md px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-led-green transition-all duration-200"
                                :class="{
                                    'border-red-500': form.errors.comment,
                                }"
                            ></textarea>
                            <div class="flex justify-between mt-1">
                                <p
                                    v-if="form.errors.comment"
                                    class="text-red-500 text-sm"
                                >
                                    {{ form.errors.comment }}
                                </p>
                                <p class="text-white/60 text-sm ml-auto">
                                    {{ form.comment.length }}/150
                                </p>
                            </div>
                        </div>

                        <div
                            class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6"
                        >
                            <div>
                                <label class="block text-white mb-2 font-medium"
                                    >Ordre d'affichage</label
                                >
                                <input
                                    v-model="form.order"
                                    type="number"
                                    class="w-full bg-deep-black border border-gaming-red rounded-md px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-led-green transition-all duration-200"
                                    :class="{
                                        'border-red-500': form.errors.order,
                                    }"
                                />
                                <p
                                    v-if="form.errors.order"
                                    class="text-red-500 mt-1 text-sm"
                                >
                                    {{ form.errors.order }}
                                </p>
                            </div>

                            <div class="space-y-3">
                                <div>
                                    <label
                                        class="block text-white mb-2 font-medium"
                                        >Options</label
                                    >
                                </div>

                                <div class="flex items-center space-x-2 mt-2">
                                    <div
                                        @click="
                                            form.is_approved = !form.is_approved
                                        "
                                        :class="[
                                            'px-4 py-2 rounded-md cursor-pointer transition-colors duration-200',
                                            form.is_approved
                                                ? 'bg-led-green text-deep-black font-medium'
                                                : 'bg-gaming-red text-white font-medium',
                                        ]"
                                    >
                                        {{
                                            form.is_approved
                                                ? "Approuvé"
                                                : "En attente"
                                        }}
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-3">
                                <div>
                                    <label
                                        class="block text-white mb-2 font-medium"
                                        >Mise en avant</label
                                    >
                                </div>

                                <div class="flex items-center space-x-2 mt-2">
                                    <div
                                        @click="
                                            form.is_featured = !form.is_featured
                                        "
                                        :class="[
                                            'px-4 py-2 rounded-md cursor-pointer transition-colors duration-200',
                                            form.is_featured
                                                ? 'bg-yellow-500 text-deep-black font-medium'
                                                : 'bg-deep-black border border-gaming-red text-white',
                                        ]"
                                    >
                                        {{
                                            form.is_featured
                                                ? "Mis en avant"
                                                : "Normal"
                                        }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-wrap gap-4 pt-2">
                            <button
                                type="submit"
                                class="bg-gaming-red text-white px-6 py-3 rounded-md hover:bg-gaming-red/90 transition duration-150 flex items-center"
                                :disabled="form.processing"
                            >
                                <span v-if="form.processing" class="mr-2">
                                    <svg
                                        class="animate-spin h-5 w-5 text-white"
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                    >
                                        <circle
                                            class="opacity-25"
                                            cx="12"
                                            cy="12"
                                            r="10"
                                            stroke="currentColor"
                                            stroke-width="4"
                                        ></circle>
                                        <path
                                            class="opacity-75"
                                            fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                        ></path>
                                    </svg>
                                </span>
                                {{ editing ? "Mettre à jour" : "Ajouter" }}
                            </button>
                            <button
                                v-if="editing"
                                @click="cancelEdit"
                                type="button"
                                class="bg-deep-black border border-gaming-red text-white px-6 py-3 rounded-md hover:bg-gaming-red/10 transition duration-150"
                            >
                                Annuler
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Liste des avis -->
                <div
                    v-if="filteredReviews.length === 0"
                    class="bg-deep-black p-6 rounded-lg shadow text-center"
                >
                    <p class="text-white/80">Aucun avis trouvé</p>
                </div>

                <div v-else class="space-y-4">
                    <!-- En-têtes du tableau - visible uniquement sur desktop -->
                    <div
                        class="hidden lg:grid bg-gaming-red/20 p-4 rounded-lg mb-4 grid-cols-12 gap-4 items-center font-semibold text-white"
                    >
                        <div
                            class="col-span-3 flex items-center cursor-pointer"
                            @click="toggleSort('name')"
                        >
                            Client
                            <ArrowUpIcon
                                v-if="
                                    sortBy === 'name' && sortDirection === 'asc'
                                "
                                class="h-4 w-4 ml-1"
                            />
                            <ArrowDownIcon
                                v-else-if="
                                    sortBy === 'name' &&
                                    sortDirection === 'desc'
                                "
                                class="h-4 w-4 ml-1"
                            />
                        </div>
                        <div
                            class="col-span-4 cursor-pointer"
                            @click="toggleSort('comment')"
                        >
                            Commentaire
                            <ArrowUpIcon
                                v-if="
                                    sortBy === 'comment' &&
                                    sortDirection === 'asc'
                                "
                                class="h-4 w-4 ml-1 inline"
                            />
                            <ArrowDownIcon
                                v-else-if="
                                    sortBy === 'comment' &&
                                    sortDirection === 'desc'
                                "
                                class="h-4 w-4 ml-1 inline"
                            />
                        </div>
                        <div
                            class="col-span-1 text-center cursor-pointer"
                            @click="toggleSort('rating')"
                        >
                            Note
                            <ArrowUpIcon
                                v-if="
                                    sortBy === 'rating' &&
                                    sortDirection === 'asc'
                                "
                                class="h-4 w-4 ml-1 inline"
                            />
                            <ArrowDownIcon
                                v-else-if="
                                    sortBy === 'rating' &&
                                    sortDirection === 'desc'
                                "
                                class="h-4 w-4 ml-1 inline"
                            />
                        </div>
                        <div
                            class="col-span-2 text-center cursor-pointer"
                            @click="toggleSort('is_approved')"
                        >
                            Statut
                            <ArrowUpIcon
                                v-if="
                                    sortBy === 'is_approved' &&
                                    sortDirection === 'asc'
                                "
                                class="h-4 w-4 ml-1 inline"
                            />
                            <ArrowDownIcon
                                v-else-if="
                                    sortBy === 'is_approved' &&
                                    sortDirection === 'desc'
                                "
                                class="h-4 w-4 ml-1 inline"
                            />
                        </div>
                        <div class="col-span-2 text-center">Actions</div>
                    </div>

                    <!-- Barre de tri mobile visible uniquement sur petit écran -->
                    <div
                        class="lg:hidden bg-gaming-red/20 p-3 rounded-lg mb-4 text-white"
                    >
                        <div class="flex justify-between items-center">
                            <div class="text-sm font-medium">Trier par:</div>
                            <div class="flex gap-2 flex-wrap">
                                <button
                                    @click="toggleSort('name')"
                                    class="px-2 py-1 rounded text-sm flex items-center"
                                    :class="{
                                        'bg-led-green text-deep-black':
                                            sortBy === 'name',
                                        'bg-deep-black/30': sortBy !== 'name',
                                    }"
                                >
                                    Client
                                    <ArrowUpIcon
                                        v-if="
                                            sortBy === 'name' &&
                                            sortDirection === 'asc'
                                        "
                                        class="h-3 w-3 ml-1"
                                    />
                                    <ArrowDownIcon
                                        v-else-if="
                                            sortBy === 'name' &&
                                            sortDirection === 'desc'
                                        "
                                        class="h-3 w-3 ml-1"
                                    />
                                </button>
                                <button
                                    @click="toggleSort('rating')"
                                    class="px-2 py-1 rounded text-sm flex items-center"
                                    :class="{
                                        'bg-led-green text-deep-black':
                                            sortBy === 'rating',
                                        'bg-deep-black/30': sortBy !== 'rating',
                                    }"
                                >
                                    Note
                                    <ArrowUpIcon
                                        v-if="
                                            sortBy === 'rating' &&
                                            sortDirection === 'asc'
                                        "
                                        class="h-3 w-3 ml-1"
                                    />
                                    <ArrowDownIcon
                                        v-else-if="
                                            sortBy === 'rating' &&
                                            sortDirection === 'desc'
                                        "
                                        class="h-3 w-3 ml-1"
                                    />
                                </button>
                                <button
                                    @click="toggleSort('is_approved')"
                                    class="px-2 py-1 rounded text-sm flex items-center"
                                    :class="{
                                        'bg-led-green text-deep-black':
                                            sortBy === 'is_approved',
                                        'bg-deep-black/30':
                                            sortBy !== 'is_approved',
                                    }"
                                >
                                    Statut
                                    <ArrowUpIcon
                                        v-if="
                                            sortBy === 'is_approved' &&
                                            sortDirection === 'asc'
                                        "
                                        class="h-3 w-3 ml-1"
                                    />
                                    <ArrowDownIcon
                                        v-else-if="
                                            sortBy === 'is_approved' &&
                                            sortDirection === 'desc'
                                        "
                                        class="h-3 w-3 ml-1"
                                    />
                                </button>
                            </div>
                        </div>
                    </div>

                    <transition-group name="list" tag="div" class="space-y-4">
                        <div
                            v-for="review in filteredReviews"
                            :key="review.id"
                            class="bg-deep-black p-4 sm:p-6 rounded-lg shadow border border-gaming-red/10 hover:border-gaming-red/40 transition-all duration-300"
                            :class="{
                                'border-yellow-500/50': review.is_featured,
                            }"
                        >
                            <!-- Vue mobile/tablette (structure en carte) -->
                            <div class="lg:hidden">
                                <div class="mb-3">
                                    <h4
                                        class="text-lg font-play font-semibold text-led-green"
                                    >
                                        {{ review.name }}
                                    </h4>
                                    <p
                                        v-if="review.email"
                                        class="text-white/60 text-sm mt-1"
                                    >
                                        {{ review.email }}
                                    </p>

                                    <div
                                        class="flex space-x-2 items-center mt-2"
                                    >
                                        <div
                                            class="flex text-yellow-400 space-x-0.5"
                                        >
                                            <span
                                                v-for="star in 5"
                                                :key="star"
                                                :class="
                                                    star <= review.rating
                                                        ? 'text-yellow-400'
                                                        : 'text-gray-600'
                                                "
                                                >★</span
                                            >
                                        </div>

                                        <span
                                            v-if="review.is_featured"
                                            class="bg-yellow-600/30 text-yellow-400 px-2 py-0.5 text-xs rounded-full"
                                        >
                                            Mis en avant
                                        </span>
                                    </div>

                                    <p class="text-white/80 mt-3">
                                        {{ review.comment }}
                                    </p>

                                    <div class="flex flex-wrap gap-2 mt-3">
                                        <span class="text-white/80 text-sm">
                                            Ajouté le
                                            {{ formatDate(review.created_at) }}
                                        </span>

                                        <span
                                            v-if="review.order > 0"
                                            class="bg-deep-black border border-gaming-red/50 px-2 py-0.5 text-xs rounded-full text-white"
                                        >
                                            Ordre: {{ review.order }}
                                        </span>
                                    </div>
                                </div>

                                <div
                                    class="flex flex-wrap justify-between items-center gap-y-3 mt-4 pt-3 border-t border-gaming-red/20"
                                >
                                    <div>
                                        <span
                                            v-if="review.is_approved"
                                            class="bg-led-green/20 text-led-green px-3 py-1 rounded-full inline-block"
                                        >
                                            Approuvé
                                        </span>
                                        <span
                                            v-else
                                            class="bg-gaming-red/20 text-gaming-red px-3 py-1 rounded-full inline-block"
                                        >
                                            En attente
                                        </span>
                                    </div>

                                    <div class="flex gap-2">
                                        <button
                                            v-if="!review.is_approved"
                                            @click="approveReview(review)"
                                            class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded transition-colors duration-200"
                                            title="Approuver"
                                        >
                                            ✓
                                        </button>
                                        <button
                                            v-if="review.is_approved"
                                            @click="rejectReview(review)"
                                            class="bg-orange-500 hover:bg-orange-600 text-white px-3 py-1 rounded transition-colors duration-200"
                                            title="Suspendre"
                                        >
                                            ⏸
                                        </button>
                                        <button
                                            @click="editReview(review)"
                                            class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded transition-colors duration-200"
                                        >
                                            Éditer
                                        </button>
                                        <button
                                            @click="confirmDelete(review)"
                                            class="bg-gaming-red hover:bg-red-600 text-white px-3 py-1 rounded transition-colors duration-200"
                                        >
                                            Supprimer
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Vue desktop (structure en grille) -->
                            <div
                                class="hidden lg:grid grid-cols-12 gap-4 items-start"
                            >
                                <div class="col-span-3">
                                    <h4
                                        class="text-lg font-play font-semibold text-led-green"
                                    >
                                        {{ review.name }}
                                    </h4>
                                    <p
                                        v-if="review.email"
                                        class="text-white/60 text-sm mt-1"
                                    >
                                        {{ review.email }}
                                    </p>
                                    <p class="text-white/80 text-sm mt-1">
                                        Ajouté le
                                        {{ formatDate(review.created_at) }}
                                    </p>

                                    <div
                                        class="flex space-x-1 items-center mt-2"
                                    >
                                        <span
                                            v-if="review.is_featured"
                                            class="bg-yellow-600/30 text-yellow-400 px-2 py-0.5 text-xs rounded-full"
                                        >
                                            Mis en avant
                                        </span>
                                        <span
                                            v-if="review.order > 0"
                                            class="bg-deep-black border border-gaming-red/50 px-2 py-0.5 text-xs rounded-full text-white"
                                        >
                                            Ordre: {{ review.order }}
                                        </span>
                                    </div>
                                </div>

                                <div class="col-span-4">
                                    <p class="text-white/80 line-clamp-3">
                                        {{ review.comment }}
                                    </p>
                                </div>

                                <div class="col-span-1 text-center">
                                    <div
                                        class="flex justify-center text-yellow-400 space-x-0.5"
                                    >
                                        <span
                                            v-for="star in 5"
                                            :key="star"
                                            :class="
                                                star <= review.rating
                                                    ? 'text-yellow-400'
                                                    : 'text-gray-600'
                                            "
                                            >★</span
                                        >
                                    </div>
                                </div>

                                <div class="col-span-2 text-center">
                                    <span
                                        v-if="review.is_approved"
                                        class="bg-led-green/20 text-led-green px-3 py-1 rounded-full max-w-24 inline-block"
                                    >
                                        Approuvé
                                    </span>
                                    <span
                                        v-else
                                        class="bg-gaming-red/20 text-gaming-red px-3 py-1 rounded-full max-w-24 inline-block"
                                    >
                                        En attente
                                    </span>
                                </div>

                                <div
                                    class="col-span-2 flex justify-center gap-2"
                                >
                                    <button
                                        v-if="!review.is_approved"
                                        @click="approveReview(review)"
                                        class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded transition-colors duration-200"
                                        title="Approuver"
                                    >
                                        ✓
                                    </button>
                                    <button
                                        v-if="review.is_approved"
                                        @click="rejectReview(review)"
                                        class="bg-orange-500 hover:bg-orange-600 text-white px-3 py-1 rounded transition-colors duration-200"
                                        title="Suspendre"
                                    >
                                        ⏸
                                    </button>
                                    <button
                                        @click="editReview(review)"
                                        class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded transition-colors duration-200"
                                    >
                                        Éditer
                                    </button>
                                    <button
                                        @click="confirmDelete(review)"
                                        class="bg-gaming-red hover:bg-red-600 text-white px-3 py-1 rounded transition-colors duration-200"
                                    >
                                        Supprimer
                                    </button>
                                </div>
                            </div>
                        </div>
                    </transition-group>
                </div>
            </div>
        </div>

        <!-- Modal de confirmation de suppression -->
        <TransitionRoot appear :show="showDeleteModal" as="template">
            <Dialog
                as="div"
                @close="showDeleteModal = false"
                class="relative z-10"
            >
                <TransitionChild
                    as="template"
                    enter="duration-300 ease-out"
                    enter-from="opacity-0"
                    enter-to="opacity-100"
                    leave="duration-200 ease-in"
                    leave-from="opacity-100"
                    leave-to="opacity-0"
                >
                    <div class="fixed inset-0 bg-black/75 transition-opacity" />
                </TransitionChild>

                <div class="fixed inset-0 overflow-y-auto">
                    <div
                        class="flex min-h-full items-center justify-center p-4 text-center"
                    >
                        <TransitionChild
                            as="template"
                            enter="duration-300 ease-out"
                            enter-from="opacity-0 scale-95"
                            enter-to="opacity-100 scale-100"
                            leave="duration-200 ease-in"
                            leave-from="opacity-100 scale-100"
                            leave-to="opacity-0 scale-95"
                        >
                            <DialogPanel
                                class="w-full max-w-md transform overflow-hidden rounded-2xl bg-deep-black border border-gaming-red p-4 sm:p-6 text-left align-middle shadow-xl transition-all"
                            >
                                <div
                                    class="flex items-center justify-center mb-5 text-gaming-red"
                                >
                                    <ExclamationTriangleIcon
                                        class="h-10 w-10 sm:h-12 sm:w-12"
                                    />
                                </div>

                                <div class="text-center">
                                    <h3
                                        class="text-lg sm:text-xl font-medium text-white mb-4"
                                    >
                                        Confirmer la suppression
                                    </h3>
                                    <p
                                        class="text-white/70 mb-6 text-sm sm:text-base"
                                    >
                                        Êtes-vous sûr de vouloir supprimer
                                        l'avis de : <br />
                                        <span
                                            class="font-semibold text-led-green break-words"
                                            >{{ reviewToDelete?.name }}</span
                                        >
                                    </p>
                                </div>

                                <div class="flex justify-center gap-4 mt-6">
                                    <button
                                        @click="showDeleteModal = false"
                                        class="inline-flex justify-center rounded-md border border-gaming-red bg-deep-black px-3 py-2 sm:px-4 sm:py-2 text-sm sm:text-base text-white hover:bg-gaming-red/10 transition-colors"
                                    >
                                        Annuler
                                    </button>
                                    <button
                                        @click="deleteReview"
                                        class="inline-flex justify-center rounded-md border border-transparent bg-gaming-red px-3 py-2 sm:px-4 sm:py-2 text-sm sm:text-base text-white hover:bg-gaming-red/90 transition-colors"
                                    >
                                        Supprimer
                                    </button>
                                </div>
                            </DialogPanel>
                        </TransitionChild>
                    </div>
                </div>
            </Dialog>
        </TransitionRoot>

        <!-- Toast de notification -->
        <div
            v-if="updateStatus"
            class="fixed bottom-4 right-4 p-4 rounded-lg shadow-lg transition-all duration-300 z-50"
            :class="{
                'bg-led-green text-white': updateStatus === 'success',
                'bg-gaming-red text-white': updateStatus === 'error',
                'bg-blue-500 text-white': updateStatus === 'loading',
            }"
        >
            <div class="flex items-center space-x-2">
                <svg
                    v-if="updateStatus === 'success'"
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5"
                    viewBox="0 0 20 20"
                    fill="currentColor"
                >
                    <path
                        fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd"
                    />
                </svg>
                <svg
                    v-if="updateStatus === 'error'"
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5"
                    viewBox="0 0 20 20"
                    fill="currentColor"
                >
                    <path
                        fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                        clip-rule="evenodd"
                    />
                </svg>
                <svg
                    v-if="updateStatus === 'loading'"
                    class="animate-spin h-5 w-5"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                >
                    <circle
                        class="opacity-25"
                        cx="12"
                        cy="12"
                        r="10"
                        stroke="currentColor"
                        stroke-width="4"
                    ></circle>
                    <path
                        class="opacity-75"
                        fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                    ></path>
                </svg>
                <span>{{ updateMessage }}</span>
            </div>
        </div>
    </AdminLayout>
</template>

<style scoped>
.list-enter-active,
.list-leave-active {
    transition: all 0.3s ease;
}
.list-enter-from,
.list-leave-to {
    opacity: 0;
    transform: translateY(20px);
}
</style>
