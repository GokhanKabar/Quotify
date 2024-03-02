# Quotify

### ChallengeS1

Théo DAVIGNY - Victor DE DOMENICO - Mohammad GONS SAIB - Gokhan KABAR

## Présentation du projet

Quotify est une plateforme de gestion de devis et de factures pour les petites et moyennes entreprises (PME).** Notre
objectif est de fournir une solution flexible qui permet aux entreprises de modéliser et gérer leurs processus
financiers de manière efficace et personnalisée. Notre application offre une gamme de fonctionnalités clés, notamment la
création, la modification et la suppression de devis et de factures, la gestion des clients et des produits, l'envoi
automatisé d'e-mails, le suivi des paiements, la génération de rapports financiers et la gestion des utilisateurs et des
rôles.

## Description du Projet

Ce projet Symfony est conçu pour répondre aux besoins spécifiques des petites et moyennes entreprises (PME) en leur
offrant une plateforme sur mesure pour la gestion de devis et de factures. Notre objectif est de fournir une solution
flexible qui permet aux entreprises de modéliser et gérer leurs processus financiers de manière efficace et
personnalisée.

## Contraintes techniques

- **Symfony** : Création d'un panel d'administration sans utiliser easyAdmin ou équivalent.
- **Intégration** : Utilisation de TailWindCSS & SASS, avec une approche mobile first et la personnalisation de thèmes.
- **UX/UI/Accessibilité** : Recherche utilisateur approfondie pour comprendre les besoins et préférences des PME, avec
  création de personas et personnalisation de l'expérience utilisateur.

## Fonctionnalités Principales

- **Création, Modification et Suppression de Devis et de Factures** : Les utilisateurs peuvent facilement créer, éditer
  et supprimer des devis et des factures. Les informations essentielles telles que les détails du client, les produits,
  les prix et les taxes peuvent être ajoutées et modifiées avec aisance.
- **Gestion des Clients** : Le système permet la gestion complète des clients, incluant l'ajout, la modification et la
  suppression des données clients. Il conserve un registre organisé et accessible de toutes les interactions avec les
  clients.
- **Gestion des Produits et des Catégories** : Les utilisateurs peuvent créer, mettre à jour et classer des produits
  dans des catégories pertinentes, facilitant ainsi la sélection et l'ajout de produits aux devis et aux factures.
- **Envoi d'E-mails Automatiques** : L'application automatise l'envoi d'e-mails pour les devis et les factures,
  garantissant une communication rapide et efficace avec les clients. Les e-mails peuvent être personnalisés et
  contiennent des liens vers les documents pertinents.
- **Suivi des Paiements et Gestion des Statuts de Paiement** : Les utilisateurs peuvent suivre les paiements effectués
  pour les factures, avec des statuts de paiement mis à jour. Cela permet un suivi précis des paiements en attente et
  des paiements reçus.
- **Génération de Rapports Financiers** : L'application génère des rapports financiers détaillés, offrant aux
  utilisateurs une vue d'ensemble des performances financières de leur entreprise. Les rapports peuvent être
  personnalisés en fonction des besoins de l'utilisateur.
- **Gestion des Utilisateurs, des Rôles et des Droits d'Accès** : Le système permet de gérer les utilisateurs,
  d'assigner des rôles spécifiques et de définir des droits d'accès pour garantir la sécurité et la confidentialité des
  données.

## Rôles Utilisateurs

- **Administrateur** : Gère les utilisateurs et les entreprises. Accès total à toutes les fonctionnalités. Rapports
  financiers globaux.
- **Entreprise** : Crée, modifie et supprime des devis et des factures. Gère les clients et les produits. Envoi
  automatisé de devis et de factures. Suivi des paiements.
- **Comptable** : Accès aux données financières pour la datavisualisation. Suivi des paiements et des statuts de
  paiement. Génération de rapports financiers.

## Fonctionnalités Bonus

- Intégration d'un modèle Freemium
- Relance automatique des demandes de paiements
- Gestion de remise sur devis/factures (par ligne ou global)
- Conversion automatique d'un devis en facture

## Liens Importants

- GitHub : [Lien du repository] (https://github.com/GokhanKabar/Quotify/)
- Démo en Ligne : [Lien de la production] (http://89.116.110.72/)
- Documentation UX/UI : [Lien vers la documentation des personas] ()

## Changements Récents & Contributions

### @Thivalaine (Théo DAVIGNY)

- Ajout d'entités, de datafixtures et d'Adminer au docker-compose
- Modification d'entités
- Intégration de la page d'accueil, modification de `base.html.twig` et ajout de 2 composants
- Modification de la BDD, datafixtures et des routes de la liste des utilisateurs
- Ajout du CRUD pour les factures et les devis (sans détails ajoutés)
- Correction de divers bugs
- Correction de toutes les routes d'entreprise et de la route du tableau de bord
- Ajout de détails pour les factures et les devis (sans intégration frontale avancée)
- Intégration de la sidebar, initialisation du front pour les factures et les devis
- Ajout d'un composant de bouton, intégration de la page de contact, personnalisation des formulaires
- Ajout d'un composant modal pour les devis et les factures
- Modification du front de l'admin, adaptation de la sidebar selon les rôles
- Correction de la conversion de devis en facture et fixation du prototype de sélection de produits
- Correction de la page d'erreur et de la conversion de devis en facture

### @Nirdeo (Victor DE DOMENICO)

- Ajout de la fonctionnalité de mail avec Symfony Mailer et Mailtrap
- Ajout des rôles
- Ajout de la liste des utilisateurs selon l'entreprise et gestion des utilisateurs avec entreprise
- Correction des routes de redirection
- Suivi des paiements
- Refactorisation de fichiers
- Mise à jour des formulaires clients
- Génération de PDF
- Correction du contrôleur CustomerController et de l'affichage des tables
- Automatisation des mails PDF
- Datavisualisation du tableau de bord
- Correction de requêtes PostgreSQL et pour Chart.js
- Ajout de rapports financiers globaux
- Mise à jour de l'édition de profil et du contrôleur de profil de l'entreprise
- Ajout du `readme.md`

### @MohaGons (Mohammad GONS SAIB)

- Fonctionnalité d'authentification et d'inscription
- Suppression du fichier `.env` et ajout à `.gitignore`
- Réinitialisation du mot de passe
- Modification des routes `routes.yaml` et création de dossiers back/front/company
- Modification de la BDD et de Docker compose
- Panneau d'administration
- Implémentation de Stripe
- Correction des routes de sécurité
- Ajout de Captcha
- Modification du lien de paiement envoyé avec les factures
- Ajout de templates d'erreur et de contrôleurs webhook
- Gestion de `.gitignore` et de diverses corrections
- Suppression de webhook
- Correction de la sécurité CRUD, ajout de dates pour les factures/devis, mise à jour de statuts

### @GokhanKabar (Gokhan KABAR)

- CRUD Utilisateur
- Ajout de l'intégration de la page d'accueil, modification de `base.html.twig` et ajout de 2 composants
- Fonctionnalité produit
- Correction du mode lumière de la sidebar
- Création de la page d'accueil et des cartes de composants
- Intégration du front de la page de connexion et du formulaire de contact
- Front du panneau d'administration, redirection après connexion réussie, correction du footer
- Mise à jour des icônes, du footer, et du tableau de bord
- Front de l'édition de profil