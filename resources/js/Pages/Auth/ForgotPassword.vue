<script setup>
import { Head, useForm } from "@inertiajs/vue3";
import AuthenticationCard from "@/Components/AuthenticationCard.vue";
import AuthenticationCardLogo from "@/Components/AuthenticationCardLogo.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";

defineProps({
    status: String,
});

const form = useForm({
    email: "",
});

const submit = () => {
    form.post(route("password.email"));
};
</script>

<template>
    <Head title="Mot de passe oublié" />

    <AuthenticationCard>
        <template #logo>
            <AuthenticationCardLogo />
        </template>

        <div class="text-center mb-6">
            <h1 class="text-3xl font-play text-gaming-red mb-2">NextConfig</h1>
            <p class="text-white text-sm">Récupération de mot de passe</p>
        </div>

        <div class="mb-4 text-sm text-white">
            Vous avez oublié votre mot de passe ? Pas de problème. Indiquez-nous
            simplement votre adresse e-mail et nous vous enverrons un lien de
            réinitialisation qui vous permettra d'en choisir un nouveau.
        </div>

        <div v-if="status" class="mb-4 font-medium text-sm text-led-green">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="email" value="Email" />
                <TextInput
                    id="email"
                    v-model="form.email"
                    type="email"
                    class="mt-1 block w-full"
                    required
                    autofocus
                    autocomplete="username"
                />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="flex items-center justify-end mt-6">
                <PrimaryButton
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Envoyer le lien de récupération
                </PrimaryButton>
            </div>
        </form>
    </AuthenticationCard>
</template>
