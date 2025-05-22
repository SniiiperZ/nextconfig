<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import DeleteUserForm from "@/Pages/Profile/Partials/DeleteUserForm.vue";
import LogoutOtherBrowserSessionsForm from "@/Pages/Profile/Partials/LogoutOtherBrowserSessionsForm.vue";
import SectionBorder from "@/Components/SectionBorder.vue";
import TwoFactorAuthenticationForm from "@/Pages/Profile/Partials/TwoFactorAuthenticationForm.vue";
import UpdatePasswordForm from "@/Pages/Profile/Partials/UpdatePasswordForm.vue";
import UpdateProfileInformationForm from "@/Pages/Profile/Partials/UpdateProfileInformationForm.vue";

defineProps({
    confirmsTwoFactorAuthentication: Boolean,
    sessions: Array,
});
</script>

<template>
    <AdminLayout title="Profil">
        <template #header>
            <div
                class="flex flex-col sm:flex-row justify-between items-center gap-4"
            >
                <h2 class="font-semibold text-xl text-white leading-tight">
                    Paramètres du profil
                </h2>
            </div>
        </template>

        <div>
            <div class="max-w-7xl mx-auto py-6 sm:py-10 px-4 sm:px-6 lg:px-8">
                <div v-if="$page.props.jetstream.canUpdateProfileInformation">
                    <UpdateProfileInformationForm
                        :user="$page.props.auth.user"
                    />

                    <SectionBorder />
                </div>

                <div v-if="$page.props.jetstream.canUpdatePassword">
                    <UpdatePasswordForm class="mt-6 sm:mt-10" />

                    <SectionBorder />
                </div>

                <div
                    v-if="
                        $page.props.jetstream.canManageTwoFactorAuthentication
                    "
                >
                    <TwoFactorAuthenticationForm
                        :requires-confirmation="confirmsTwoFactorAuthentication"
                        class="mt-6 sm:mt-10"
                    />

                    <SectionBorder />
                </div>

                <LogoutOtherBrowserSessionsForm
                    :sessions="sessions"
                    class="mt-6 sm:mt-10"
                />

                <template
                    v-if="$page.props.jetstream.hasAccountDeletionFeatures"
                >
                    <SectionBorder />

                    <DeleteUserForm class="mt-6 sm:mt-10" />
                </template>
            </div>
        </div>
    </AdminLayout>
</template>
