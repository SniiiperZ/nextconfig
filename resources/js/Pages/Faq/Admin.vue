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
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-white leading-tight">
                    Gestion de la FAQ
                </h2>
                <div class="relative">
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
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Formulaire -->
                <div
                    id="faq-form"
                    class="bg-deep-black p-6 rounded-lg shadow mb-8 border border-gaming-red/30 hover:border-gaming-red/70 transition-all duration-300"
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

                        <div class="flex gap-4 pt-2">
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

                <!-- Liste des FAQs -->
                <div
                    v-if="filteredFaqs.length === 0"
                    class="bg-deep-black p-6 rounded-lg shadow text-center"
                >
                    <p class="text-white/80">Aucune question trouvée</p>
                </div>

                <div v-else class="space-y-4">
                    <div
                        class="bg-gaming-red/20 p-4 rounded-lg mb-4 grid grid-cols-6 gap-4 items-center font-semibold text-white"
                    >
                        <div
                            class="col-span-3 flex items-center cursor-pointer"
                            @click="toggleSort('question')"
                        >
                            Question
                            <ArrowUpIcon
                                v-if="
                                    sortBy === 'question' &&
                                    sortDirection === 'asc'
                                "
                                class="h-4 w-4 ml-1"
                            />
                            <ArrowDownIcon
                                v-else-if="
                                    sortBy === 'question' &&
                                    sortDirection === 'desc'
                                "
                                class="h-4 w-4 ml-1"
                            />
                        </div>
                        <div
                            class="cursor-pointer"
                            @click="toggleSort('order')"
                        >
                            Ordre
                            <ArrowUpIcon
                                v-if="
                                    sortBy === 'order' &&
                                    sortDirection === 'asc'
                                "
                                class="h-4 w-4 ml-1 inline"
                            />
                            <ArrowDownIcon
                                v-else-if="
                                    sortBy === 'order' &&
                                    sortDirection === 'desc'
                                "
                                class="h-4 w-4 ml-1 inline"
                            />
                        </div>
                        <div
                            class="cursor-pointer"
                            @click="toggleSort('is_visible')"
                        >
                            Visibilité
                            <ArrowUpIcon
                                v-if="
                                    sortBy === 'is_visible' &&
                                    sortDirection === 'asc'
                                "
                                class="h-4 w-4 ml-1 inline"
                            />
                            <ArrowDownIcon
                                v-else-if="
                                    sortBy === 'is_visible' &&
                                    sortDirection === 'desc'
                                "
                                class="h-4 w-4 ml-1 inline"
                            />
                        </div>
                        <div class="text-center">Actions</div>
                    </div>

                    <transition-group name="list" tag="div" class="space-y-4">
                        <div
                            v-for="faq in filteredFaqs"
                            :key="faq.id"
                            class="bg-deep-black p-6 rounded-lg shadow border border-gaming-red/10 hover:border-gaming-red/40 transition-all duration-300"
                        >
                            <div class="grid grid-cols-6 gap-4 items-start">
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
                                class="w-full max-w-md transform overflow-hidden rounded-2xl bg-deep-black border border-gaming-red p-6 text-left align-middle shadow-xl transition-all"
                            >
                                <div
                                    class="flex items-center justify-center mb-5 text-gaming-red"
                                >
                                    <ExclamationTriangleIcon
                                        class="h-12 w-12"
                                    />
                                </div>

                                <div class="text-center">
                                    <h3
                                        class="text-xl font-medium text-white mb-4"
                                    >
                                        Confirmer la suppression
                                    </h3>
                                    <p class="text-white/70 mb-6">
                                        Êtes-vous sûr de vouloir supprimer la
                                        question : <br />
                                        <span
                                            class="font-semibold text-led-green"
                                            >{{ faqToDelete?.question }}</span
                                        >
                                    </p>
                                </div>

                                <div class="flex justify-center gap-4 mt-6">
                                    <button
                                        @click="showDeleteModal = false"
                                        class="inline-flex justify-center rounded-md border border-gaming-red bg-deep-black px-4 py-2 text-white hover:bg-gaming-red/10 transition-colors"
                                    >
                                        Annuler
                                    </button>
                                    <button
                                        @click="deleteItem"
                                        class="inline-flex justify-center rounded-md border border-transparent bg-gaming-red px-4 py-2 text-white hover:bg-gaming-red/90 transition-colors"
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
