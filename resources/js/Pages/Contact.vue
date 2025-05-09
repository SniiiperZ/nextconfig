<script setup>
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import PublicLayout from "@/Layouts/PublicLayout.vue";
import FormInput from "@/Components/FormInput.vue";
import Toast from "@/Components/Toast.vue";

const form = useForm({
    name: "",
    email: "",
    phone: "",
    subject: "",
    message: "",
});

// Remplacer le message de succès par un toast
const toast = ref({
    show: false,
    message: "",
    type: "success",
});

const submit = () => {
    form.post(route("contact.send"), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            // Afficher le toast au lieu du message inline
            toast.value = {
                show: true,
                message:
                    "Votre message a été envoyé avec succès ! Nous vous contacterons bientôt.",
                type: "success",
            };

            setTimeout(() => {
                toast.value.show = false;
            }, 6000);
        },
    });
};

const closeToast = () => {
    toast.value.show = false;
};
</script>

<template>
    <PublicLayout title="Contact">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Bannière du devis -->
                <div
                    class="bg-deep-black p-8 rounded-lg border border-led-green mb-8 text-center"
                >
                    <h2 class="text-2xl font-play text-gaming-red mb-4">
                        Besoin d'un PC sur mesure ?
                    </h2>
                    <p class="text-white mb-4">
                        Utilisez notre devis d'orientation pour nous indiquer
                        vos besoins spécifiques et obtenir une proposition
                        personnalisée.
                    </p>
                    <a
                        :href="route('devis')"
                        class="inline-block px-6 py-3 bg-led-green text-deep-black font-medium rounded-md hover:bg-opacity-90 transition duration-150"
                    >
                        Accéder au devis PC
                    </a>
                </div>

                <div class="grid md:grid-cols-2 gap-12">
                    <!-- Informations de contact -->
                    <div
                        class="bg-deep-black p-8 rounded-lg border border-gaming-red"
                    >
                        <h2 class="text-2xl font-play text-gaming-red mb-6">
                            Contactez-nous
                        </h2>

                        <div class="space-y-6">
                            <div class="flex items-center space-x-4">
                                <svg
                                    class="w-6 h-6 text-led-green"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"
                                    />
                                </svg>
                                <span class="text-white"
                                    >+32 (0) 123 456 789</span
                                >
                            </div>

                            <div class="flex items-center space-x-4">
                                <svg
                                    class="w-6 h-6 text-led-green"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                                    />
                                </svg>
                                <span class="text-white"
                                    >contact@nextconfig.be</span
                                >
                            </div>

                            <div class="flex items-center space-x-4">
                                <svg
                                    class="w-6 h-6 text-led-green"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                                    />
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                                    />
                                </svg>
                                <span class="text-white">Belgique</span>
                            </div>
                        </div>
                    </div>

                    <!-- Formulaire de contact - Mise à jour du style pour correspondre au devis -->
                    <div
                        class="bg-deep-black p-8 rounded-lg border border-gaming-red"
                    >
                        <h2 class="text-2xl font-play text-gaming-red mb-6">
                            Envoyez-nous un message
                        </h2>

                        <form @submit.prevent="submit" class="space-y-6">
                            <!-- Suppression du message de succès inline qui est remplacé par le toast -->
                            <div
                                class="bg-gaming-red/5 p-4 rounded-lg border border-gaming-red/30 mb-6"
                            >
                                <h3
                                    class="text-xl font-play text-led-green mb-4"
                                >
                                    Vos informations
                                </h3>

                                <FormInput
                                    v-model="form.name"
                                    label="Nom complet"
                                    :error="form.errors.name"
                                />

                                <FormInput
                                    v-model="form.email"
                                    type="email"
                                    label="Email"
                                    :error="form.errors.email"
                                />

                                <FormInput
                                    v-model="form.phone"
                                    label="Téléphone"
                                    :error="form.errors.phone"
                                />
                            </div>

                            <div
                                class="bg-gaming-red/5 p-4 rounded-lg border border-gaming-red/30 mb-6"
                            >
                                <h3
                                    class="text-xl font-play text-led-green mb-4"
                                >
                                    Votre message
                                </h3>

                                <FormInput
                                    v-model="form.subject"
                                    label="Sujet"
                                    :error="form.errors.subject"
                                />

                                <div class="mb-4">
                                    <label
                                        class="block text-white text-sm font-medium mb-2"
                                    >
                                        Message
                                    </label>
                                    <textarea
                                        v-model="form.message"
                                        rows="4"
                                        class="w-full px-3 py-2 bg-deep-black border border-gaming-red rounded-md text-white focus:outline-none focus:ring-2 focus:ring-led-green"
                                    ></textarea>
                                    <p
                                        v-if="form.errors.message"
                                        class="mt-1 text-sm text-gaming-red"
                                    >
                                        {{ form.errors.message }}
                                    </p>
                                </div>
                            </div>

                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="w-full px-4 py-3 bg-gaming-red text-white rounded-md hover:bg-opacity-90 transition duration-150 disabled:opacity-50 font-play"
                            >
                                Envoyer mon message
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ajout de la notification toast flottante -->
        <Toast
            :show="toast.show"
            :message="toast.message"
            :type="toast.type"
            position="bottom-right"
            @close="closeToast"
        />
    </PublicLayout>
</template>
