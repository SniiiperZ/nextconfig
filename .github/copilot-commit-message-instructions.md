## 1. Structure de Base

Chaque commit doit être clair, percutant et explicite, et toujours en français en suivant cette syntaxe :

<Emoji> <Titre succinct> : <Description détaillée et pertinente>

**Exemple :**

✨ Ajout de l'authentification : implémentation de l'authentification JWT avec gestion des erreurs

---

## 2. Liste des Emojis et Leur Utilisation

-   **✨ Nouvelle Fonctionnalité**  
    **Utilisation :** Pour introduire une nouvelle fonctionnalité ou module important.  
    **Exemple :**

✨ Ajout de la recherche intelligente : intégration de l'algorithme de tri avancé

-   **🐛 Correction de Bug**  
    **Utilisation :** Pour corriger un bug ou résoudre un comportement erroné.  
    **Exemple :**

🐛 Correction du crash : résolution du bug de déconnexion intempestive

-   **🎨 Amélioration du Style / Refactoring Légère**  
    **Utilisation :** Pour des ajustements de code visant à améliorer la lisibilité ou la structure sans altérer le comportement de l'application.  
    **Exemple :**

🎨 Nettoyage du code : amélioration de la fonction de parsing

-   **♻️ Refactoring**  
    **Utilisation :** Pour une restructuration profonde du code sans ajout de fonctionnalités.  
    **Exemple :**

♻️ Refactorisation du module d'authentification pour une meilleure maintenance

-   **🔥 Suppression de Code**  
    **Utilisation :** Pour retirer du code obsolète ou inutile.  
    **Exemple :**

🔥 Suppression des anciennes méthodes de connexion

-   **💄 Amélioration de l'UI**  
    **Utilisation :** Pour toute modification affectant l'interface utilisateur.  
    **Exemple :**

💄 Mise à jour du design du header de l'application

-   **📝 Documentation**  
    **Utilisation :** Pour mettre à jour ou ajouter de la documentation et des commentaires explicatifs.  
    **Exemple :**

📝 Ajout de la documentation pour l'API de paiement

-   **🚀 Amélioration des Performances**  
    **Utilisation :** Pour optimiser le code et améliorer ses performances globales.  
    **Exemple :**

🚀 Optimisation des requêtes SQL pour réduire la latence

-   **🔧 Maintenance / Configuration**  
    **Utilisation :** Pour des modifications de configuration ou la maintenance du projet.  
    **Exemple :**

🔧 Mise à jour des dépendances du projet

---

## 3. Intégration avec GitHub Co-Pilot

Pour que GitHub Co-Pilot suive ce format, ajoute le template suivant dans la documentation de ton projet ou dans ton fichier de configuration :

```javascript
/*
Template de message de commit pour Co-Pilot :

<Emoji> <Titre succinct> : <Description détaillée>

Exemples :
- ✨ [Fonctionnalité] : Ajout de X avec gestion des erreurs
- 🐛 [Correctif] : Correction du bug de déconnexion

Sois concis et précis, et laisse ton code raconter son histoire.
*/
```

## 4. Conseils d'Utilisation

    Clarté et Précision : Sois direct et explicite ; ton commit doit expliquer le "quoi" et le "pourquoi".

    Cohérence : Utilise toujours le même emoji pour le même type de modification. La constance est la clé pour des commits dignes d’un samouraï !

    Validation : Vérifie que chaque commit reflète exactement les changements apportés. Un commit précis, c'est comme un coup de katana bien placé, tranchant et efficace ! ⚔️
