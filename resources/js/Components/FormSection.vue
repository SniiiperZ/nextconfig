<script setup>
import { computed, useSlots } from "vue";
import SectionTitle from "./SectionTitle.vue";

defineEmits(["submitted"]);

const slots = useSlots();
const hasActions = computed(() => !!slots.actions);
</script>

<template>
    <div class="md:grid md:grid-cols-3 md:gap-6">
        <SectionTitle>
            <template #title>
                <slot name="title" />
            </template>
            <template #description>
                <slot name="description" />
            </template>
        </SectionTitle>

        <div class="mt-5 md:mt-0 md:col-span-2">
            <form @submit.prevent="$emit('submitted')">
                <div
                    class="px-4 py-5 sm:p-6 bg-deep-black border border-gaming-red/30 shadow sm:rounded-lg hover:border-gaming-red/60 transition-all duration-300"
                    :class="hasActions ? 'sm:rounded-t-lg' : 'sm:rounded-lg'"
                >
                    <div class="grid grid-cols-6 gap-4 sm:gap-6">
                        <slot name="form" />
                    </div>
                </div>

                <div
                    v-if="hasActions"
                    class="flex flex-wrap items-center justify-end px-4 py-3 bg-deep-black/80 border-t border-gaming-red/30 text-end sm:px-6 shadow sm:rounded-b-lg gap-3"
                >
                    <slot name="actions" />
                </div>
            </form>
        </div>
    </div>
</template>
