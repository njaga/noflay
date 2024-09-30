<script setup>
import { Head } from '@inertiajs/vue3';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';

defineProps({
    terms: String,
});
</script>

<template>
    <Head title="Conditions d'Utilisation" />

    <div class="font-sans text-gray-900 antialiased bg-gray-100">
        <div class="pt-4">
            <div class="min-h-screen flex flex-col items-center pt-8 sm:pt-12">
                <div class="mb-8">
                    <AuthenticationCardLogo />
                </div>

                <section class="w-full max-w-5xl">
                    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                        <div class="bg-indigo-500 py-6 px-8">
                            <h1 class="text-3xl font-bold text-white">CONDITIONS D'UTILISATION</h1>
                            <p class="text-gray-200 text-sm">Dernière mise à jour le 17 octobre 2022</p>
                        </div>
                        <div class="p-8">
                            <p class="text-gray-600 mb-8">
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse varius enim in eros elementum tristique. Duis cursus, mi quis viverra ornare, eros dolor interdum nulla, ut commodo diam libero vitae erat. Aenean faucibus nibh et justo cursus id rutrum lorem imperdiet. Nunc ut sem vitae risus tristique posuere.
                            </p>

                            <div class="space-y-8">
                                <div>
                                    <h2 class="text-lg font-bold text-indigo-500 mb-4">SERVICES</h2>
                                    <p class="text-gray-600">
                                        Noflay App offre une gamme complète de fonctionnalités pour simplifier la gestion des entreprises immobilières. L'application fournira les services convenus avec professionnalisme, créativité et expertise technique, tout en respectant les normes de l'industrie, les principes de conception et les meilleures pratiques. Les détails spécifiques, les livrables, les délais et la tarification de chaque projet seront décrits dans un accord ou une proposition séparés, mutuellement convenus par l'entreprise et le client.
                                    </p>
                                </div>

                                <div>
                                    <h2 class="text-lg font-bold text-indigo-500 mb-4">RESPONSABILITÉS DU CLIENT</h2>
                                    <p class="text-gray-600">
                                        Le client s'engage à fournir des informations, des matériaux et des commentaires exacts et opportuns nécessaires à la réalisation réussie du projet. Le client est responsable de l'obtention des autorisations, licences ou droits d'auteur nécessaires pour les matériaux fournis à l'entreprise pour utilisation dans le projet, y compris mais sans s'y limiter les logos, les images, le texte et tout autre propriété intellectuelle. Le client reconnaît que les retards ou les défaillances dans la fourniture des matériaux ou des commentaires requis peuvent avoir un impact sur les délais, les livrables et le succès global du projet.
                                    </p>
                                </div>

                                <div>
                                    <h2 class="text-lg font-bold text-indigo-500 mb-4">PROPRIÉTÉ INTELLECTUELLE</h2>
                                    <p class="text-gray-600">
                                        Tous les droits de propriété intellectuelle, y compris mais sans s'y limiter les droits d'auteur et les marques déposées, sur les livrables finaux créés par l'entreprise seront transférés au client sur réception du paiement intégral, sauf accord écrit contraire. Le client garantit que tout matériel fourni à l'entreprise pour utilisation dans le projet ne porte pas atteinte aux droits de propriété intellectuelle d'un tiers.
                                    </p>
                                </div>

                                <div>
                                    <h2 class="text-lg font-bold text-indigo-500 mb-4">PAIEMENT</h2>
                                    <p class="text-gray-600">
                                        Le client s'engage à payer à l'entreprise les honoraires convenus pour les services rendus. Les modalités de paiement, notamment le montant, le mode et le calendrier, seront précisés dans l'accord ou la proposition séparés. L'entreprise se réserve le droit de suspendre ou de mettre fin aux services en cas de non-paiement ou de paiement tardif.
                                    </p>
                                </div>

                                <div>
                                    <h2 class="text-lg font-bold text-indigo-500 mb-4">CONFIDENTIALITÉ</h2>
                                    <p class="text-gray-600">
                                        L'entreprise et le client s'engagent à garder confidentielles toutes les informations exclusives ou sensibles divulguées au cours du projet. Les deux parties prendront des mesures raisonnables pour protéger ces informations contre tout accès ou divulgation non autorisés.
                                    </p>
                                </div>

                                <div>
                                    <h2 class="text-lg font-bold text-indigo-500 mb-4">LIMITATION DE RESPONSABILITÉ</h2>
                                    <p class="text-gray-600">
                                        L'entreprise ne sera pas tenue responsable des dommages directs, indirects, accessoires ou consécutifs résultant de l'utilisation ou de l'impossibilité d'utiliser les services fournis. Le client reconnaît que la responsabilité de l'entreprise est limitée au montant payé pour les services rendus.
                                    </p>
                                </div>

                                <div>
                                    <h2 class="text-lg font-bold text-indigo-500 mb-4">RÉSILIATION</h2>
                                    <p class="text-gray-600">
                                        L'une ou l'autre partie peut résilier le présent contrat sur préavis écrit si l'autre partie viole une disposition essentielle et ne remédie pas à la violation dans un délai raisonnable. En cas de résiliation, le client paiera à l'entreprise les services fournis jusqu'à la date de résiliation.
                                    </p>
                                </div>

                                <div>
                                    <h2 class="text-lg font-bold text-indigo-500 mb-4">DROIT APPLICABLE</h2>
                                    <p class="text-gray-600">
                                        Le présent contrat est régi et interprété conformément aux lois du Sénégal. Tout différend découlant du présent contrat sera soumis à la juridiction exclusive des tribunaux du Sénégal.
                                    </p>
                                </div>

                                <div class="border-t border-gray-200 pt-6">
                                    <p class="text-gray-600 text-sm">
                                        En accédant, en parcourant ou en utilisant les services de l'application Noflay App, vous reconnaissez, comprenez et acceptez expressément que vous avez lu attentivement, compris et consenti pleinement à être légalement lié par toutes les dispositions, les termes et les conditions énoncés dans les présentes Conditions d'Utilisation, y compris tous les accords, politiques, directives ou modifications supplémentaires qui y sont référencés ou incorporés.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</template>
