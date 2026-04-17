# PLAN_MODE.md

## Objectif courant
Passer d'un corpus local GRETA à une première verticale produit crédible et plus opérationnelle : doctrine, inventaire, architecture WordPress, thème/plugin custom, homepage premium, archive de parcours, détail de parcours PHP, séquences module/lesson, dashboard éditorial et prévisualisation locale multi-pages.

## Findings du repository
- Répertoire initial quasi vide hors `pdfs_context/`
- 29 PDF détectés
- 233 pages environ
- 0 fichier PHP existant
- 0 installation WordPress existante
- 0 dépôt Git existant

## Domaines découverts
- Linux fondamentaux
- Prompt, chemins, commandes, `ls`, `man`, `grep`
- Réseau Linux, validation d'interface et DNS
- LAMP
- MariaDB : sécurisation, utilisateurs, droits
- MariaDB : modélisation, types, clés étrangères
- MariaDB : SQL conditionnel et TPs
- Apache2 : déploiement, multisites, proxy, VirtualHosts
- Apache2 : `.htaccess`, protection, regex, URL rewriting
- PHP + MariaDB sur Apache
- WordPress avec Apache2, PHP et MariaDB
- Nginx + PHP-FPM

## Phase 1 — Découverte
- [x] inventaire des fichiers
- [x] lecture de `CONTEXT.txt`
- [x] extraction des premières pages PDF
- [x] détection des doublons/versions

## Phase 2 — Synthèse
- [x] définir les learning paths
- [x] poser le modèle WordPress
- [x] décider du split thème/plugin
- [x] définir la narration "support de cours + preuve technique"

## Phase 3 — Documentation
- [x] créer `CODEX.md`
- [x] créer `PLAN_MODE.md`
- [x] créer `SOUL.md`
- [x] créer `project-data/inventory.json`

## Phase 4 — Première verticale forte
- [x] scaffold du plugin WordPress
- [x] scaffold du thème WordPress
- [x] homepage premium
- [x] hero avec stack visible
- [x] archive learning paths
- [x] détail d'un parcours PHP
- [x] détail module
- [x] détail lesson
- [x] détail resource
- [x] composant resource card
- [x] dashboard éditorial enrichi
- [x] navigation séquentielle module / lesson
- [x] preview HTML locale multi-pages

## Phase 5 — Déploiement readiness
- [x] Dockerfile Railway
- [x] configuration PHP complémentaire
- [x] README local + Railway
- [x] variables d'environnement documentées

## Vertical slices prévues
1. Slice A
   Doctrine + inventaire + structures WordPress + homepage + archive + single path PHP.
2. Slice B
   Resource library + dashboard + relations module/leçon + filtres Alpine.
3. Slice C
   Import administratif + raffinements responsive + contenu enrichi.
4. Slice D
   Polishing déploiement GitHub/Railway + stratégie média/PDF.

## Done / Next / Later
### Done
- comprendre le corpus réel
- figer la carte des domaines
- décider l'architecture produit
- écrire la doctrine projet
- créer l'inventaire JSON source-of-truth
- scaffold WordPress thème + plugin
- rendre modules et lessons navigables
- transformer les ressources en fiches dédiées avec accès PDF
- produire une preview statique du premier slice
- détailler la preview avec vues path / module / lesson / resource
- préparer Docker + Railway
- exposer la santé du corpus dans le dashboard

### Next
- valider le rendu dans un runtime WordPress réel
- enrichir le détail module / lesson avec plus d'extraction depuis les supports
- décider si les PDF restent copiés dans `uploads` ou deviennent des attachments WordPress
- rendre le dashboard administrable depuis WordPress sans dépendre uniquement du JSON

### Later
- vrai système de progression utilisateur
- import de PDF comme pièces jointes WordPress
- moteur de recherche avancé
- analytics pédagogiques

## Blockers
- pas de binaire `php` local
- pas de `composer`
- pas de `wp-cli`
- pas de WordPress core local

## Assumptions
- le premier slice peut être construit comme code WordPress + preview statique fidèle
- Railway déploiera via Dockerfile racine
- la base MariaDB sera fournie comme service séparé
- l'utilisateur publiera ensuite ce repo sur GitHub

## Notes de rollback
- ne pas modifier le corpus `pdfs_context/`
- garder l'inventaire normalisé dans `project-data/inventory.json`
- si la couche WordPress évolue, la preview reste un filet de sécurité produit

## Test plan
- validation structure fichiers
- validation JSON
- revue visuelle de la preview HTML
- revue logique des templates PHP
- vérification des routes et variables documentées

## Deployment plan
1. publier le repo sur GitHub
2. connecter le repo comme source d'un service Railway
3. laisser Railway détecter le `Dockerfile`
4. créer un service MariaDB sur Railway
5. renseigner les variables WordPress/MariaDB
6. déployer
7. finaliser l'installation WordPress dans le navigateur

## Smallest next valuable slice
Brancher ce slice sur un vrai runtime WordPress pour vérifier seed, templates single, dashboard et bibliothèque documentaire dans la chaîne complète Apache/PHP/MariaDB.
