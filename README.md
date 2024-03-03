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

## Changements Récents & Contributions

### @Thivalaine (Théo DAVIGNY)

- Création des entités
- Création des jeux d'essais (Datafixtures)
- Création crud devis et factures avec ajout dynamique de lignes de devis/factures
- Implémentation composants (table, bouton, modal et footer)
- Conversion de devis en facture
- Status de paiement lorsqu'une facture a été payée
- Responsive de la sidebar et du composant table
- Création du front du back (listage des clients, des entreprises, ...) et page d'accueil du tableau de bord
- Création d'un template de formulaire pour la partie back
- Implémentation des images pour les produits

### @Nirdeo (Victor DE DOMENICO)

- Creation de la fonctionnalité de mail
- Creation de la fonctionnalité de role
- Creation crud customer
- Creation chart report
- Creation chart global report
- Creation suivi et statut de paiement
- Creation edit profil
- Creation pagination

### @MohaGons (Mohammad GONS SAIB)

- Création CRUD company
- Reset password
- Envoi d'E-mails Automatiques
- Sécurité des crud en fonction des entreprises
- Implémentation stripe
- Captcha
- Template error

### @GokhanKabar (Gokhan KABAR)

- Implémentation du login et de son front
- Création du crud user
- Création du crud product
- Création du front de la page Accueil, À propos, Contact
- Implémentation du component navbar, sidebar, sidebar admin, cards rows et cards rows reverse
- Modification du footer pour le rendre responsive
- Création front stripe success et cancel
- Création du front reset password, paramètre du compte
- Création des templates pour les envoies de mail
- Déploiement du site en production sur un vps avec une stack LEMP