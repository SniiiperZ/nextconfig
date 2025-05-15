<script setup>
import { Head } from "@inertiajs/vue3";
import PublicLayout from "@/Layouts/PublicLayout.vue";
</script>

<template>
    <PublicLayout title="Mentions légales">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="bg-deep-black border border-gaming-red overflow-hidden shadow-xl sm:rounded-lg"
                >
                    <div class="p-6 text-white">
                        <div class="text-center mb-8">
                            <h1
                                class="text-4xl font-play text-gaming-red section-title inline-block"
                            >
                                Mentions légales
                            </h1>
                        </div>

                        <div class="legal-content">
                            <h2>1. Informations générales</h2>
                            <p>
                                Nom du site : NextConfig<br />
                                Adresse du site :
                                https://www.nextconfig.sniperz.be<br />
                                Propriétaire : SCHREURS Dylan<br />
                                Statut : Travail réalisé dans le cadre d'un TFE
                                à IFOSUP<br />
                                Responsable de la publication : SCHREURS
                                Dylan<br />
                                Email de contact : dyjoke@nextconfig.sniperz.be
                            </p>

                            <h2>2. Hébergement</h2>
                            <p>
                                Hébergeur : o2switch<br />
                                Chemin des Pardiaux, 63000 Clermont-Ferrand,
                                France<br />
                                SAS au capital de 100 000 €<br />
                                RCS Clermont-Ferrand<br />
                                SIRET : 510 909 80700024<br />
                                Support : support@o2switch.fr<br />
                                Site : https://www.o2switch.fr
                            </p>

                            <h2>3. Développement</h2>
                            <p>
                                Ce site a été développé par SCHREURS Cedric dans
                                le cadre de son Travail de Fin d'Études (TFE) au
                                sein de l'IFOSUP, encadré par ces superviseurs
                                Martin Bruno et Six Thibault.<br />
                                Email : cedric.schreurs@ifosup.wavre.be
                            </p>

                            <h2>4. Propriété intellectuelle</h2>
                            <p>
                                L'ensemble des contenus (textes, images, logos,
                                vidéos, fichiers, codes sources) présents sur ce
                                site sont la propriété exclusive de SCHREURS
                                Dylan ou de ses partenaires, sauf mention
                                contraire. Toute reproduction, distribution ou
                                réutilisation, même partielle, est interdite
                                sans autorisation écrite préalable.
                            </p>

                            <h2>5. Responsabilité</h2>
                            <p>
                                L'utilisateur est seul responsable de
                                l'utilisation qu'il fait du site et de ses
                                contenus. Malgré toute l'attention portée au bon
                                fonctionnement du site, des erreurs ou des
                                interruptions de service peuvent survenir.
                                SCHREURS Dylan ne pourra être tenu responsable
                                des dommages directs ou indirects liés à
                                l'utilisation du site.
                            </p>

                            <h2>6. Liens externes</h2>
                            <p>
                                Le site peut contenir des liens vers des sites
                                tiers. Ces liens sont proposés à titre
                                informatif. SCHREURS Dylan n'a aucun contrôle
                                sur leur contenu et décline toute responsabilité
                                quant aux informations qui y sont présentées.
                            </p>

                            <h2>7. Données personnelles</h2>
                            <p>
                                La gestion des données personnelles est
                                détaillée dans notre Politique de
                                confidentialité. L'utilisateur dispose de droits
                                (accès, rectification, suppression...) qu'il
                                peut exercer à tout moment en contactant :
                                dyjoke@nextconfig.sniperz.be
                            </p>

                            <h2>8. Cookies</h2>
                            <p>
                                Le site utilise des cookies nécessaires à son
                                bon fonctionnement, ainsi que des cookies de
                                mesure d'audience anonymes. Un bandeau
                                d'information permet à l'utilisateur d'accepter
                                ou refuser ces cookies lors de sa première
                                visite.
                            </p>

                            <h2>9. Droit applicable</h2>
                            <p>
                                Les présentes mentions sont régies par le droit
                                belge. En cas de litige, les tribunaux
                                compétents seront ceux du ressort de Wavre.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>

<style scoped>
.section-title {
    position: relative;
    display: inline-block;
    padding-bottom: 10px;
}

.section-title::after {
    content: "";
    position: absolute;
    width: 60%;
    height: 3px;
    bottom: 0;
    left: 20%;
    background: linear-gradient(
        90deg,
        transparent,
        rgba(236, 64, 122, 0.8),
        transparent
    );
}

.legal-content {
    line-height: 1.6;
}

.legal-content h2 {
    font-size: 1.5rem;
    color: #4ade80; /* Couleur led-green */
    margin-top: 2rem;
    margin-bottom: 1rem;
    font-family: "Play", sans-serif;
}

.legal-content p {
    margin-bottom: 1rem;
}

.legal-content ul {
    list-style-type: disc;
    margin-left: 1.5rem;
    margin-bottom: 1rem;
}
</style>
