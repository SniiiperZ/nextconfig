<script setup>
import { ref, computed } from "vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { useForm, router } from "@inertiajs/vue3";
import AdminSearchHeader from "@/Components/Admin/AdminSearchHeader.vue";
import AdminSortHeader from "@/Components/Admin/AdminSortHeader.vue";
import ConfirmDeleteModal from "@/Components/Admin/ConfirmDeleteModal.vue";
import StatusToast from "@/Components/Admin/StatusToast.vue";
import AdminFormButtons from "@/Components/Admin/AdminFormButtons.vue";

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
const showDeleteModal = ref(false);
const faqToDelete = ref(null);
const searchQuery = ref("");
const sortBy = ref("order");
const sortDirection = ref("asc");
const updateStatus = ref(null);
const updateMessage = ref("");

// Définir les colonnes pour le tri
const sortColumns = [
    { key: "question", label: "Question", colSpan: 3 },
    { key: "order", label: "Ordre", colSpan: 1 },
    { key: "is_visible", label: "Visibilité", colSpan: 1 },
    { key: "actions", label: "Actions", colSpan: 1 },
];

// Filtrage et tri des FAQs
const filteredFaqs = computed(() => {
    let filtered = [...props.faqs];

    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        filtered = filtered.filter(
            (faq) =>
                faq.question.toLowerCase().includes(query) ||
                faq.answer.toLowerCase().includes(query)
        );
    }

    filtered.sort((a, b) => {
        let comparison = 0;
        if (a[sortBy.value] > b[sortBy.value]) {
            comparison = 1;
        } else if (a[sortBy.value] < b[sortBy.value]) {
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
        sortDirection.value = "asc";
    }
};

const submit = () => {
    if (editing.value) {
        form.put(route("admin.faq.update", editing.value.id), {
            preserveScroll: true,
            onSuccess: () => {
                editing.value = null;
                form.reset();
                updateStatus.value = "success";
                updateMessage.value = "FAQ mise à jour avec succès !";
                setTimeout(() => {
                    updateStatus.value = null;
                    updateMessage.value = "";
                }, 3000);
            },
        });
    } else {
        form.post(route("admin.faq.store"), {
            preserveScroll: true,
            onSuccess: () => {
                form.reset();
                updateStatus.value = "success";
                updateMessage.value = "FAQ ajoutée avec succès !";
                setTimeout(() => {
                    updateStatus.value = null;
                    updateMessage.value = "";
                }, 3000);
            },
        });
    }
};

const editFaq = (faq) => {
    editing.value = faq;
    form.question = faq.question;
    form.answer = faq.answer;
    form.order = faq.order;
    form.is_visible = faq.is_visible;

    // Scroll to form
    setTimeout(() => {
        document
            .querySelector("#faq-form")
            .scrollIntoView({ behavior: "smooth" });
    }, 100);
};

const cancelEdit = () => {
    editing.value = null;
    form.reset();
};

const confirmDelete = (faq) => {
    faqToDelete.value = faq;
    showDeleteModal.value = true;
};

const deleteItem = () => {
    router.delete(route("admin.faq.destroy", faqToDelete.value.id), {
        preserveScroll: true,
        onFinish: () => {
            showDeleteModal.value = false;
            faqToDelete.value = null;
        },
    });
};
</script>

<template>
    <AdminLayout title="Gestion FAQ">
        <template #header>
            <AdminSearchHeader
                title="Gestion de la FAQ"
                v-model:searchQuery="searchQuery"
            />
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Formulaire -->
                <div
                    id="faq-form"
                    class="bg-deep-black p-4 sm:p-6 rounded-lg shadow mb-8 border border-gaming-red/30 hover:border-gaming-red/70 transition-all duration-300"
                >
                    <h3
                        class="text-xl font-play font-semibold text-led-green mb-4"
                    >
                        {{
                            editing
                                ? "Modifier une question"
                                : "Ajouter une nouvelle question"
                        }}
                    </h3>

                    <form @submit.prevent="submit" class="space-y-5">
                        <div>
                            <label class="block text-white mb-2 font-medium"
                                >Question</label
                            >
                            <input
                                v-model="form.question"
                                type="text"
                                class="w-full bg-deep-black border border-gaming-red rounded-md px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-led-green transition-all duration-200"
                                :class="{
                                    'border-red-500': form.errors.question,
                                }"
                            />
                            <p
                                v-if="form.errors.question"
                                class="text-red-500 mt-1 text-sm"
                            >
                                {{ form.errors.question }}
                            </p>
                        </div>

                        <div>
                            <label class="block text-white mb-2 font-medium"
                                >Réponse</label
                            >
                            <textarea
                                v-model="form.answer"
                                rows="5"
                                class="w-full bg-deep-black border border-gaming-red rounded-md px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-led-green transition-all duration-200"
                                :class="{
                                    'border-red-500': form.errors.answer,
                                }"
                            ></textarea>
                            <p
                                v-if="form.errors.answer"
                                class="text-red-500 mt-1 text-sm"
                            >
                                {{ form.errors.answer }}
                            </p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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

                            <div>
                                <label class="block text-white mb-2 font-medium"
                                    >Statut de visibilité</label
                                >
                                <div class="flex items-center space-x-3 mt-3">
                                    <div
                                        @click="form.is_visible = true"
                                        :class="[
                                            'px-4 py-2 rounded-md cursor-pointer transition-colors duration-200',
                                            form.is_visible
                                                ? 'bg-led-green text-deep-black font-medium'
                                                : 'bg-deep-black border border-gaming-red text-white',
                                        ]"
                                    >
                                        Visible
                                    </div>
                                    <div
                                        @click="form.is_visible = false"
                                        :class="[
                                            'px-4 py-2 rounded-md cursor-pointer transition-colors duration-200',
                                            !form.is_visible
                                                ? 'bg-gaming-red text-white font-medium'
                                                : 'bg-deep-black border border-gaming-red text-white',
                                        ]"
                                    >
                                        Masqué
                                    </div>
                                </div>
                            </div>
                        </div>

                        <AdminFormButtons
                            :processing="form.processing"
                            :is-editing="!!editing"
                            @cancel="cancelEdit"
                        />
                    </form>
                </div>

                <!-- Liste des FAQs -->
                <div
                    v-if="filteredFaqs.length === 0"
                    class="bg-deep-black p-6 rounded-lg shadow text-center"
                >
                    <p class="text-white/80">Aucune question trouvée</p>
                </div>

                <div v-else class="space-y-4">
                    <AdminSortHeader
                        :sort-by="sortBy"
                        :sort-direction="sortDirection"
                        :columns="sortColumns"
                        @sort="toggleSort"
                    />

                    <transition-group name="list" tag="div" class="space-y-4">
                        <div
                            v-for="faq in filteredFaqs"
                            :key="faq.id"
                            class="bg-deep-black p-4 sm:p-6 rounded-lg shadow border border-gaming-red/10 hover:border-gaming-red/40 transition-all duration-300"
                        >
                            <!-- Vue mobile (structure en carte) -->
                            <div class="md:hidden">
                                <h3
                                    class="text-xl font-play font-semibold text-led-green mb-2"
                                >
                                    {{ faq.question }}
                                </h3>
                                <p class="text-white/80 mb-4">
                                    {{ faq.answer }}
                                </p>

                                <div
                                    class="flex flex-wrap gap-4 mt-4 justify-between"
                                >
                                    <div
                                        class="flex flex-wrap gap-2 items-center"
                                    >
                                        <div>
                                            <span
                                                class="text-white text-sm mr-2"
                                                >Ordre:</span
                                            >
                                            <span
                                                class="bg-deep-black border border-gaming-red/50 px-3 py-1 rounded-full"
                                            >
                                                {{ faq.order }}
                                            </span>
                                        </div>
                                        <div>
                                            <span
                                                class="text-white text-sm mr-2"
                                                >Statut:</span
                                            >
                                            <span
                                                v-if="faq.is_visible"
                                                class="bg-led-green/20 text-led-green px-3 py-1 rounded-full"
                                            >
                                                Visible
                                            </span>
                                            <span
                                                v-else
                                                class="bg-gaming-red/20 text-gaming-red px-3 py-1 rounded-full"
                                            >
                                                Masqué
                                            </span>
                                        </div>
                                    </div>

                                    <div class="flex gap-2 mt-2 sm:mt-0">
                                        <button
                                            @click="editFaq(faq)"
                                            class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded transition-colors duration-200"
                                        >
                                            Éditer
                                        </button>
                                        <button
                                            @click="confirmDelete(faq)"
                                            class="bg-gaming-red hover:bg-red-600 text-white px-3 py-1 rounded transition-colors duration-200"
                                        >
                                            Supprimer
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Vue tablette/desktop (structure en grille) -->
                            <div
                                class="hidden md:grid grid-cols-6 gap-4 items-start"
                            >
                                <div class="col-span-3">
                                    <h3
                                        class="text-xl font-play font-semibold text-led-green mb-2"
                                    >
                                        {{ faq.question }}
                                    </h3>
                                    <p class="text-white/80">
                                        {{ faq.answer }}
                                    </p>
                                </div>
                                <div>
                                    <span
                                        class="bg-deep-black border border-gaming-red/50 px-3 py-1 rounded-full"
                                    >
                                        {{ faq.order }}
                                    </span>
                                </div>
                                <div>
                                    <span
                                        v-if="faq.is_visible"
                                        class="bg-led-green/20 text-led-green px-3 py-1 rounded-full"
                                    >
                                        Visible
                                    </span>
                                    <span
                                        v-else
                                        class="bg-gaming-red/20 text-gaming-red px-3 py-1 rounded-full"
                                    >
                                        Masqué
                                    </span>
                                </div>
                                <div class="flex justify-center gap-2">
                                    <button
                                        @click="editFaq(faq)"
                                        class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded transition-colors duration-200"
                                    >
                                        Éditer
                                    </button>
                                    <button
                                        @click="confirmDelete(faq)"
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

        <ConfirmDeleteModal
            :show="showDeleteModal"
            :item-name="faqToDelete?.question"
            item-label="la question"
            @close="showDeleteModal = false"
            @confirm="deleteItem"
        />

        <StatusToast :status="updateStatus" :message="updateMessage" />
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
