# Hello

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
Des dates de publication sont générées par les fixtures afin que les questions puissent être triées selon les exigences de l'ennoncé. Les réponses ont également une date, mais je n'ai délibérément pas cherché à avoir des dates/heures cohérentes entre les questions et réponses liées.

Faute de temps (et notamment cause de changement d'approche de la gestion des rôles qui m'a obligé à recommencer tout samedi matin ... ), l'intégration du site est minimaliste, elle permet néanmoins d'assurer une lecture qui me semble correcte.

## Avancement

Sauf ommission involontaire, les différentes fonctionnalités ont été implémentées (hors bonus).

Dans le dossier `docs` : une micro intégration qui à servi de base pour la suite ainsi qu'un MCD.

Le MCD prévoyait l'utilisation d'une table spécifique pour la gestion des rôles, mais la difficulté d'intégrer un rôle par défaut à la création d'un nouveau user (insertion d'un objet rôle prédéterminé) m'a empéché de conserver cette approche. Le rôle est donc au format json stocké en string en db, je ne suis pas très satisfait de ma manière d'exploiter la donnée mais ça marche ... 

Le MCD envisageait également la prise en compte du bonus des votes.

### Un bug, que je n'arrive pas à solutionner, persiste :

A l'envoi du formulaire de modification d'un user si (et seulement si) le mot de passe est laissé vide :
`Expected argument of type "string", "NULL" given at property path "password".`

Sur moviedb, grâce à `'empty_data' => ''`, nous avions pu gérer l'erreur sur le formulaire de modification d'un user sans saisir de mot de passe si pas de modification dessus.
Malgré la réutilisation du code de moviedb, et la consultation d'issues sur github / Stackoverflow, je n'ai pas réussi à reproduire le comportement que nous avions sur moviedb.