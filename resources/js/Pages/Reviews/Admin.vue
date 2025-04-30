<script setup>
import { ref } from "vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { useForm } from "@inertiajs/vue3";

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

const submit = () => {
    if (editing.value) {
        form.put(route("admin.reviews.update", editing.value.id), {
            preserveScroll: true,
            onSuccess: () => {
                editing.value = null;
                form.reset();
            },
        });
    } else {
        form.post(route("admin.reviews.store"), {
            preserveScroll: true,
            onSuccess: () => form.reset(),
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
    });
};

const rejectReview = (review) => {
    useForm({
        is_approved: false,
    }).patch(route("admin.reviews.approval", review.id), {
        preserveScroll: true,
    });
};

const deleteReview = (review) => {
    if (
        confirm(
            `Êtes-vous sûr de vouloir supprimer cet avis de ${review.name} ?`
        )
    ) {
        useForm().delete(route("admin.reviews.destroy", review.id), {
            preserveScroll: true,
        });
    }
};

// Fonction pour rendre les étoiles de notation
const renderStars = (rating) => {
    let stars = [];
    for (let i = 1; i <= 5; i++) {
        stars.push(i <= rating);
    }
    return stars;
};
</script>

<template>
    <AdminLayout title="Gestion des Avis">
        <template #header>
            <h2 class="font-semibold text-xl text-white leading-tight">
                Gestion des Avis Clients
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Formulaire d'ajout/modification d'avis -->
                <div class="bg-deep-black p-6 rounded-lg shadow mb-6">
                    <form @submit.prevent="submit" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-white mb-2">Nom</label>
                                <input
                                    v-model="form.name"
                                    type="text"
                                    class="w-full bg-deep-black border border-gaming-red rounded-md px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-led-green"
                                />
                                <p
                                    v-if="form.errors.name"
                                    class="text-red-500 text-sm mt-1"
                                >
                                    {{ form.errors.name }}
                                </p>
                            </div>

                            <div>
                                <label class="block text-white mb-2"
                                    >Email (optionnel)</label
                                >
                                <input
                                    v-model="form.email"
                                    type="email"
                                    class="w-full bg-deep-black border border-gaming-red rounded-md px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-led-green"
                                />
                                <p
                                    v-if="form.errors.email"
                                    class="text-red-500 text-sm mt-1"
                                >
                                    {{ form.errors.email }}
                                </p>
                            </div>
                        </div>

                        <div>
                            <label class="block text-white mb-2">Note</label>
                            <div class="flex space-x-2">
                                <button
                                    v-for="star in 5"
                                    :key="star"
                                    type="button"
                                    @click="form.rating = star"
                                    class="text-2xl focus:outline-none"
                                    :class="
                                        star <= form.rating
                                            ? 'text-yellow-400'
                                            : 'text-gray-600'
                                    "
                                >
                                    ★
                                </button>
                            </div>
                            <p
                                v-if="form.errors.rating"
                                class="text-red-500 text-sm mt-1"
                            >
                                {{ form.errors.rating }}
                            </p>
                        </div>

                        <div>
                            <label class="block text-white mb-2"
                                >Commentaire
                                <span class="text-sm text-white/60"
                                    >(max. 150 caractères)</span
                                ></label
                            >
                            <textarea
                                v-model="form.comment"
                                rows="4"
                                maxlength="150"
                                class="w-full bg-deep-black border border-gaming-red rounded-md px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-led-green"
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

                        <div class="flex flex-wrap gap-6">
                            <div>
                                <label class="block text-white mb-2"
                                    >Ordre</label
                                >
                                <input
                                    v-model="form.order"
                                    type="number"
                                    class="w-full bg-deep-black border border-gaming-red rounded-md px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-led-green"
                                />
                            </div>

                            <div class="flex items-center">
                                <label class="flex items-center">
                                    <input
                                        v-model="form.is_approved"
                                        type="checkbox"
                                        class="rounded border-gaming-red bg-deep-black text-led-green shadow-sm focus:ring-led-green"
                                    />
                                    <span class="text-white">Approuvé</span>
                                </label>
                            </div>

                            <div class="flex items-center">
                                <label class="flex items-center">
                                    <input
                                        v-model="form.is_featured"
                                        type="checkbox"
                                        class="rounded border-gaming-red bg-deep-black text-led-green shadow-sm focus:ring-led-green"
                                    />
                                    <span class="text-white">Mis en avant</span>
                                </label>
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <button
                                type="submit"
                                class="bg-gaming-red text-white px-4 py-2 rounded-md hover:bg-opacity-90 transition duration-150"
                            >
                                {{ editing ? "Mettre à jour" : "Ajouter" }}
                            </button>
                            <button
                                v-if="editing"
                                @click="cancelEdit"
                                type="button"
                                class="bg-deep-black border border-gaming-red text-white px-4 py-2 rounded-md hover:bg-gaming-red/10 transition duration-150"
                            >
                                Annuler
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Liste des avis -->
                <div class="bg-deep-black p-6 rounded-lg shadow">
                    <h3 class="text-xl text-led-green mb-6">
                        Liste des avis clients
                    </h3>

                    <div
                        v-if="!reviews.length"
                        class="text-white text-center py-4"
                    >
                        Aucun avis à afficher pour le moment.
                    </div>

                    <div v-else class="space-y-6">
                        <div
                            v-for="review in reviews"
                            :key="review.id"
                            class="border border-gaming-red rounded-lg p-4"
                            :class="{
                                'border-yellow-500': review.is_featured,
                                'opacity-70': !review.is_approved,
                            }"
                        >
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <h4 class="text-lg text-led-green">
                                            {{ review.name }}
                                        </h4>
                                        <span
                                            v-if="review.is_featured"
                                            class="bg-yellow-600 text-white px-2 py-0.5 text-xs rounded"
                                        >
                                            Mis en avant
                                        </span>
                                        <span
                                            v-if="!review.is_approved"
                                            class="bg-gray-600 text-white px-2 py-0.5 text-xs rounded"
                                        >
                                            En attente
                                        </span>
                                    </div>

                                    <div
                                        class="flex text-yellow-400 text-lg mt-1"
                                    >
                                        <span
                                            v-for="(
                                                filled, index
                                            ) in renderStars(review.rating)"
                                            :key="index"
                                        >
                                            {{ filled ? "★" : "☆" }}
                                        </span>
                                    </div>

                                    <div class="text-sm text-white/60 mt-1">
                                        {{
                                            new Date(
                                                review.created_at
                                            ).toLocaleDateString("fr-FR")
                                        }}
                                    </div>
                                </div>

                                <div class="flex space-x-2">
                                    <button
                                        v-if="!review.is_approved"
                                        @click="approveReview(review)"
                                        class="bg-green-500 text-white px-3 py-1 rounded"
                                    >
                                        Approuver
                                    </button>
                                    <button
                                        v-if="review.is_approved"
                                        @click="rejectReview(review)"
                                        class="bg-orange-500 text-white px-3 py-1 rounded"
                                    >
                                        Suspendre
                                    </button>
                                    <button
                                        @click="editReview(review)"
                                        class="bg-blue-500 text-white px-3 py-1 rounded"
                                    >
                                        Modifier
                                    </button>
                                    <button
                                        @click="deleteReview(review)"
                                        class="bg-red-500 text-white px-3 py-1 rounded"
                                    >
                                        Supprimer
                                    </button>
                                </div>
                            </div>

                            <div>
                                <p class="text-white/80 whitespace-pre-line">
                                    {{ review.comment }}
                                </p>
                                <p
                                    v-if="review.email"
                                    class="text-white/60 text-sm mt-2"
                                >
                                    Email: {{ review.email }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
