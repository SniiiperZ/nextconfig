<script setup>
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import StarRating from "@/Components/StarRating.vue";

const emit = defineEmits(["submitted"]);

const showForm = ref(false);
const form = useForm({
    name: "",
    email: "",
    rating: 5,
    comment: "",
});

const toggleForm = () => {
    showForm.value = !showForm.value;
};

const setRating = (value) => {
    form.rating = value;
};

const submit = () => {
    form.post(route("reviews.store"), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            showForm.value = false;
            emit("submitted");
        },
    });
};
</script>

<template>
    <div class="mt-8 flex justify-center">
        <button
            @click="toggleForm"
            class="inline-flex items-center px-4 py-2 border border-transparent text-base font-medium rounded-md text-white bg-gaming-red hover:bg-opacity-90 transition duration-150"
        >
            <span v-if="!showForm">Laisser un avis</span>
            <span v-else>Annuler</span>
        </button>
    </div>

    <div
        v-if="showForm"
        class="mt-4 bg-deep-black border border-gaming-red rounded-lg p-6 animate-fade-in"
    >
        <h3 class="text-xl font-play text-led-green mb-4 text-center">
            Votre avis compte
        </h3>

        <form @submit.prevent="submit" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-white mb-2">Votre nom *</label>
                    <input
                        v-model="form.name"
                        type="text"
                        class="w-full bg-gray-800 text-white rounded px-3 py-2"
                        required
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
                        class="w-full bg-gray-800 text-white rounded px-3 py-2"
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
                <label class="block text-white mb-2 text-center"
                    >Votre note *</label
                >
                <div class="flex justify-center space-x-2">
                    <button
                        v-for="star in 5"
                        :key="star"
                        type="button"
                        @click="setRating(star)"
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
                    class="text-red-500 text-sm mt-1 text-center"
                >
                    {{ form.errors.rating }}
                </p>
            </div>

            <div>
                <label class="block text-white mb-2"
                    >Votre commentaire *
                    <span class="text-sm text-white/60"
                        >(max. 150 caractères)</span
                    ></label
                >
                <textarea
                    v-model="form.comment"
                    rows="4"
                    maxlength="150"
                    class="w-full bg-gray-800 text-white rounded px-3 py-2"
                    required
                ></textarea>
                <div class="flex justify-between mt-1">
                    <p v-if="form.errors.comment" class="text-red-500 text-sm">
                        {{ form.errors.comment }}
                    </p>
                    <p class="text-white/60 text-sm ml-auto">
                        {{ form.comment.length }}/150
                    </p>
                </div>
            </div>

            <div class="flex justify-between items-center">
                <p class="text-white/60 text-sm">
                    Les champs marqués d'un * sont obligatoires.
                    <br />Votre avis sera affiché après modération.
                </p>
                <button
                    type="submit"
                    class="bg-gaming-red text-white px-4 py-2 rounded"
                    :disabled="form.processing"
                >
                    Envoyer
                </button>
            </div>
        </form>
    </div>
</template>

<style scoped>
.animate-fade-in {
    animation: fadeIn 0.3s ease-in-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
