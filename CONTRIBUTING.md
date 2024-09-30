# Guide de Contribution à NOFLAY

Tout d'abord, merci d'envisager de contribuer à NOFLAY ! C'est grâce à des personnes comme vous que NOFLAY continue de s'améliorer et de grandir. Ce document vous guidera à travers le processus de contribution.

## Table des matières

1. [Code de Conduite](#code-de-conduite)
2. [Que dois-je savoir avant de commencer ?](#que-dois-je-savoir-avant-de-commencer)
3. [Comment puis-je contribuer ?](#comment-puis-je-contribuer)
   - [Signaler des bugs](#signaler-des-bugs)
   - [Suggérer des améliorations](#suggérer-des-améliorations)
   - [Votre première contribution de code](#votre-première-contribution-de-code)
   - [Pull Requests](#pull-requests)
4. [Style de code](#style-de-code)
5. [Conventions de commit](#conventions-de-commit)
6. [Questions ?](#questions)

## Code de Conduite

Ce projet et tous ses participants sont régis par le [Code de Conduite de NOFLAY](CODE_OF_CONDUCT.md). En participant, vous êtes censé respecter ce code. Veuillez signaler tout comportement inacceptable à [contact@ndiagandiaye.com](mailto:contact@ndiagandiaye.com).

## Que dois-je savoir avant de commencer ?

NOFLAY est une plateforme de gestion immobilière développée avec Laravel, Vue.js, et d'autres technologies modernes. Avant de contribuer, assurez-vous de vous familiariser avec :

- [Laravel](https://laravel.com/docs)
- [Vue.js](https://vuejs.org/guide/introduction.html)
- [Inertia.js](https://inertiajs.com/)
- [Tailwind CSS](https://tailwindcss.com/docs)

## Comment puis-je contribuer ?

### Signaler des bugs

Les bugs sont suivis comme des [issues GitHub](https://github.com/njaga/noflay/issues). Après avoir déterminé que votre problème n'est pas déjà signalé, créez une issue en fournissant les informations suivantes :

- Utilisez un titre clair et descriptif.
- Décrivez les étapes exactes pour reproduire le problème.
- Fournissez des exemples spécifiques pour démontrer les étapes.
- Décrivez le comportement que vous avez observé après avoir suivi les étapes.
- Expliquez quel comportement vous attendiez à la place et pourquoi.
- Incluez des captures d'écran si possible.

### Suggérer des améliorations

Les suggestions d'amélioration sont également suivies via [GitHub issues](https://github.com/njaga/noflay/issues). Lorsque vous créez une suggestion d'amélioration :

- Utilisez un titre clair et descriptif.
- Fournissez une description détaillée de l'amélioration suggérée.
- Expliquez pourquoi cette amélioration serait utile pour la plupart des utilisateurs de NOFLAY.
- Précisez le comportement actuel et en quoi votre suggestion le modifierait.

### Votre première contribution de code

Vous voulez contribuer au code ? Super ! Voici les étapes à suivre :

1. Fork le dépôt et créez votre branche à partir de `main`.
2. Si vous avez ajouté du code qui devrait être testé, ajoutez des tests.
3. Si vous avez changé des APIs, mettez à jour la documentation.
4. Assurez-vous que la suite de tests passe.
5. Assurez-vous que votre code respecte les conventions de style.
6. Créez votre pull request !

### Pull Requests

- Remplissez le modèle de pull request. Ce modèle vous aide à fournir toutes les informations nécessaires pour que nous puissions examiner votre PR efficacement.
- N'incluez pas de modifications sans rapport dans la PR. Si vous avez des changements sans rapport, soumettez-les dans des PRs séparées.
- Mettez à jour la documentation si nécessaire.
- Maintenez une bonne qualité de commit.

## Style de code

- Utilisez 4 espaces pour l'indentation.
- Suivez les [conventions de codage de Laravel](https://laravel.com/docs/master/contributions#coding-style) pour le PHP.
- Pour le JavaScript et Vue.js, suivez le [guide de style de Vue](https://vuejs.org/style-guide/).
- Utilisez des noms de variables et de fonctions descriptifs.
- Commentez votre code lorsque nécessaire, mais préférez un code auto-documenté.

## Conventions de commit

Nous suivons les [Conventional Commits](https://www.conventionalcommits.org/) pour nos messages de commit. Cela nous aide à générer automatiquement des changelogs et à déterminer facilement la nature des changements. Voici quelques exemples :

- `feat: ajouter la fonctionnalité de recherche de propriétés`
- `fix: corriger le bug d'affichage dans le tableau de bord`
- `docs: mettre à jour le guide d'installation`
- `style: formater le code selon les directives de style`
- `refactor: restructurer le module de facturation`
- `test: ajouter des tests pour le module d'authentification`

## Questions ?

Si vous avez des questions ou besoin d'aide, n'hésitez pas à :

- Ouvrir une [issue](https://github.com/njaga/noflay/issues) pour des questions générales.
- Contacter directement l'équipe à [contact@ndiagandiaye.com](mailto:contact@ndiagandiaye.com) pour des questions plus spécifiques ou sensibles.

Merci encore pour votre intérêt à contribuer à NOFLAY. Nous avons hâte de voir vos contributions !
