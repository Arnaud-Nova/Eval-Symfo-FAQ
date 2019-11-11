# FAQ

## Objectif

Développer un site de questions/réponses sur le modèle de [Quora](https://www.quora.com/), ou encore [StackOverflow](https://stackoverflow.com)

- Le site répertorie des **questions** (auteur, intitulé, corps de la question), et plusieurs **réponses** (auteur, corps de la réponse).
- **Les visiteurs doivent pouvoir créer un compte sur le site**  (revient à créer un nouvel utilisateur), ils deviennent alors **utilisateurs**.
- Les **utilisateurs inscrits posent les questions**, et peuvent également **proposer des réponses** aux questions déjà posées.
- **Tout le monde peut consulter** les contenus, mais **il faut être identifié pour pouvoir poser une question ou proposer une réponse**.

Des modérateurs peuvent masquer des questions ou réponses jugées hors charte, celles-ci leur restent visibles.

Un back office permet à l'administrateur de gérer les rôles des différents utilisateurs enregistrés.

## A noter :

Des fixtures existent sur ce projet, celles-ci génèrent :

    - 4 users (login et mdp identiques) dont les rôles sont précisés ci-dessous:
        - user => membre
        - user2 => membre
        - modo => modérateur
        - admin => administrateur
    - des questions
    - des réponses
    - des tags
Des dates de publication sont générées par les fixtures afin que les questions puissent être triées selon les exigences de l'énoncé. Les réponses ont également une date, mais je n'ai délibérément pas cherché à avoir des dates/heures cohérentes entre les questions et réponses liées.

L'intégration du site est minimaliste car ce n'est pas le but de cet exercice, elle permet néanmoins d'assurer une lecture correcte.

