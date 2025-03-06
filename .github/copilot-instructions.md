# Instructions pour le développement avec Laravel 11.x et Inertia

## 📌 Contexte du projet

Ce projet est un site vitrine professionnel dédié à l'assemblage de PC Gaming sur mesure pour **Schreurs Dylan**. Il est développé en **Laravel 11**, avec **Vue.js 3** et **Inertia.js** pour une approche full-stack moderne.

---

## Structure technique

-   Framework: Laravel 11.x
-   Frontend: Inertia.js avec Vue 3
-   CSS: Tailwind CSS
-   Layout principal: `AdminLayout.vue`
-   Base de données : MySQL (Laravel DB par défaut)
-   Authentification : Laravel Jetstream avec Inertia.js
-   Sécurité : Middleware, CSRF, Auth, 2FA (optionnel)
-   Hébergement : o2switch avec SSL (Let's Encrypt) et option CDN Cloudflare

---

## Règles générales

### 1. Navigation

-   Toute nouvelle route doit être ajoutée dans `routes/web.php`
-   Pour chaque lien de navigation ajouté, mettre à jour :
    -   Menu principal dans `AdminLayout.vue`
    -   Menu mobile (hamburger) dans la section responsive
    -   Les composants `NavLink` et `ResponsiveNavLink` correspondants
    -   N'emploie plus jamais `Kernel.php`

Exemple de route dans `routes/web.php` :

```php
use Inertia\Inertia;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return Inertia::render('Home');
})->name('home');
```

### 2. Layouts

-   Utiliser `AdminLayout.vue` comme layout administrateur et `PublicLayout.vue` comme layout publique
-   Structure type d'une page :

```vue
<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
</script>

<template>
    <AdminLayout title="Titre Page">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Titre Section
            </h2>
        </template>

        <div class="py-12">
            <!-- Contenu -->
        </div>
    </AdminLayout>
</template>
```

### 3. Format de réponse

-   Prend le temps d'analyser correctement la question
-   Donne-moi TOUJOURS les liens vers les fichiers où tu me proposes des modifications.

---

## ⚙️ Configuration et Setup

### 📂 Structure des dossiers

```bash
/resources/js/
  ├── Pages/        # Pages principales Vue.js
  ├── Components/   # Composants réutilisables
  ├── Layouts/      # Layouts globaux
  ├── Stores/       # (optionnel) Gestion d'état
  ├── Utils/        # (optionnel) Fonctions utilitaires
```

### 🔧 Installation

```sh
# Cloner le repo
git clone https://github.com/user/repo.git site-pc-gaming
cd site-pc-gaming

# Installer les dépendances
composer install
npm install

# Configurer l'environnement
cp .env.example .env
php artisan key:generate
php artisan migrate --seed

# Lancer le projet
php artisan serve & npm run dev
```

---

## ✨ Bonnes Pratiques avec Copilot

### ✅ Bonnes utilisations

-   **Générer du code répétitif** : Routes, migrations, composants Vue.
-   **Autocompléter des fonctions Laravel/Vue.js**.
-   **Proposer des optimisations de code**.
-   **Suggérer des algorithmes et structures** (ex: gestion de filtres pour un configurateur PC).

### ❌ Mauvaises pratiques

-   **Ne pas copier sans comprendre** : Toujours valider le code proposé.
-   **Éviter d'utiliser Copilot pour générer du code non sécurisé** (ex: requêtes SQL brutes).
-   **Ne pas ignorer les bonnes pratiques Laravel/Vue.js**.

---

## 🚀 Workflow Git avec Copilot

### 🔄 Branching

```sh
git checkout -b feature/configurateur-pc
git commit -m "Ajout du configurateur PC"
git push origin feature/configurateur-pc
```

### 🔍 Code Review

1. Toujours reviewer le code généré.
2. Ajouter des tests si nécessaire.
3. Respecter les conventions de code.

---

## 🎯 Objectifs du Projet

-   **Page d'accueil** ✅
-   **Portfolio des réalisations** 🛠
-   **Blog technique** 🛠
-   **Système de prise de rendez-vous** 🛠
-   **Configurateur PC basique** 🛠
-   **Dashboard admin** 🛠

**📅 Version finale prévue : Mai 2025** 🚀🔥
