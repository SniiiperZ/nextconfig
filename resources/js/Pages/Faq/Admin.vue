<script setup>
import { ref } from "vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { useForm } from "@inertiajs/vue3";

const props = defineProps({
    faqs: Array,
});

const form = useForm({
    question: "",
    answer: "",
    order: 0,
    is_visible: true,
});

const editing = ref(null);

const submit = () => {
    if (editing.value) {
        form.put(route("admin.faq.update", editing.value.id), {
            preserveScroll: true,
            onSuccess: () => {
                editing.value = null;
                form.reset();
            },
        });
    } else {
        form.post(route("admin.faq.store"), {
            preserveScroll: true,
            onSuccess: () => form.reset(),
        });
    }
};

const editFaq = (faq) => {
    editing.value = faq;
    form.question = faq.question;
    form.answer = faq.answer;
    form.order = faq.order;
    form.is_visible = faq.is_visible;
};

const cancelEdit = () => {
    editing.value = null;
    form.reset();
};
</script>

<template>
    <AdminLayout title="Gestion FAQ">
        <template #header>
            <h2 class="font-semibold text-xl text-white leading-tight">
                Gestion de la FAQ
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Formulaire -->
                <div class="bg-deep-black p-6 rounded-lg shadow mb-6">
                    <form @submit.prevent="submit" class="space-y-4">
                        <div>
                            <label class="block text-white mb-2"
                                >Question</label
                            >
                            <input
                                v-model="form.question"
                                type="text"
                                class="w-full bg-deep-black border border-gaming-red rounded-md px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-led-green"
                            />
                        </div>

                        <div>
                            <label class="block text-white mb-2">Réponse</label>
                            <textarea
                                v-model="form.answer"
                                rows="4"
                                class="w-full bg-deep-black border border-gaming-red rounded-md px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-led-green"
                            ></textarea>
                        </div>

                        <div class="flex gap-4">
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

                            <div>
                                <label class="block text-white mb-2"
                                    >Visible</label
                                >
                                <input
                                    v-model="form.is_visible"
                                    type="checkbox"
                                    class="rounded border-gaming-red bg-deep-black text-led-green shadow-sm focus:ring-led-green"
                                />
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

                <!-- Liste des FAQs -->
                <div class="space-y-4">
                    <div
                        v-for="faq in faqs"
                        :key="faq.id"
                        class="bg-deep-black p-6 rounded-lg shadow"
                    >
                        <div class="flex justify-between items-start mb-4">
                            <h3
                                class="text-xl font-play font-semibold text-led-green"
                            >
                                {{ faq.question }}
                            </h3>
                            <div class="flex gap-2">
                                <button
                                    @click="editFaq(faq)"
                                    class="bg-blue-500 text-white px-3 py-1 rounded"
                                >
                                    Éditer
                                </button>
                                <button
                                    @click="
                                        $inertia.delete(
                                            route('admin.faq.destroy', faq.id)
                                        )
                                    "
                                    class="bg-red-500 text-white px-3 py-1 rounded"
                                >
                                    Supprimer
                                </button>
                            </div>
                        </div>
                        <p class="text-white/80">{{ faq.answer }}</p>
                        <div class="mt-2 text-sm text-white/60">
                            Ordre: {{ faq.order }} |
                            {{ faq.is_visible ? "Visible" : "Masqué" }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
