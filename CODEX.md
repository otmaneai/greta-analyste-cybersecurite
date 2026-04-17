# CODEX.md

## Identité officielle
- Nom du projet : GRETA ANALYSTE CYBERSÉCURITÉ
- Slug technique : `greta-analyste-cybersecurite`
- Statut : construction initiale à partir d'un corpus local de supports GRETA

## Vision
Construire une plateforme premium de parcours d'apprentissage qui centralise les supports GRETA tout en servant de démonstration technique appliquée des technologies étudiées pendant la formation.

## Double identité du produit
1. Support structuré de cours : organiser les PDF, notes, synthèses, TPs et révisions.
2. Projet vitrine appliqué : montrer que le produit lui-même est construit avec PHP, HTML, Tailwind CSS, Alpine.js, MariaDB, Apache2 et WordPress.

## Public cible
- Apprenant principal : stagiaire GRETA en cybersécurité / admin système / web.
- Public secondaire : recruteurs, formateurs, pairs techniques.
- Cas d'usage : réviser, retrouver un support, comprendre une chaîne technique, démontrer une montée en compétence par le produit lui-même.

## Stack fondatrice
- PHP
- HTML
- Tailwind CSS
- Alpine.js
- MariaDB
- Apache2
- WordPress

## Stratégie d'hébergement
- Source of truth : GitHub
- Déploiement cible : Railway
- Architecture d'exécution : image Docker basée sur WordPress + Apache + PHP
- Base de données : service MariaDB séparé sur Railway

## Repository map
- `pdfs_context/` : corpus source local GRETA, 29 PDF + 1 fichier contexte
- `project-data/inventory.json` : inventaire normalisé du contenu
- `wp-content/themes/greta-analyste-cybersecurite/` : thème WordPress custom
- `wp-content/plugins/greta-learning-platform/` : plugin WordPress custom pour structures éditoriales
- `preview/` : prévisualisation HTML de la première verticale design/content
- `docker/` : configuration de conteneur et surcharges PHP/Apache
- `CODEX.md` : vérité produit/architecture
- `PLAN_MODE.md` : plan d'exécution incrémental
- `SOUL.md` : identité de marque et règles d'expérience

## Résumé de l'inventaire
- 29 PDF détectés
- 233 pages environ
- 1 fichier `CONTEXT.txt` très volumineux, utile comme archive de prompts et contexte de synthèse
- Aucun code applicatif existant
- Aucun dépôt Git initial

## Constat de départ
- Le projet ne démarre pas d'un site existant mais d'un corpus documentaire.
- Le contenu couvre Linux, réseau, DNS, LAMP, MariaDB, Apache2, PHP, WordPress et Nginx/PHP-FPM.
- Plusieurs documents sont des variantes ou versions révisées du même thème.
- Le besoin principal n'est pas de stocker des fichiers, mais de transformer ce corpus en architecture éditoriale cohérente.

## Domaines de contenu détectés
1. Fondamentaux Linux
2. Réseau Linux et DNS
3. Stack LAMP et culture base de données
4. Administration MariaDB
5. Modélisation et SQL MariaDB
6. TPs MariaDB
7. Déploiement Apache2 et multisites
8. Sécurité Apache, `.htaccess` et réécriture d'URL
9. Développement PHP web
10. Déploiement WordPress
11. Nginx et PHP-FPM

## Problèmes de source détectés
- Nommage hétérogène : `Partie_`, `Support_`, `Jour_4_`, variantes `_v1`, `_v2`, suffixes dates.
- Doublons ou quasi-doublons :
  - `Partie_1_Aujourdhui_MariaDB_Droits_GRETA.pdf`
  - `Partie_1_Aujourdhui_MariaDB_Droits_GRETA_v2.pdf`
  - `Jour_4_GRETA_Commerce_MariaDB_ImportCSV.pdf`
  - `Jour_4_GRETA_Commerce_MariaDB_ImportCSV_v2.pdf`
  - `Support_Apache_Rewrite_HTACCESS_Client4_GRETA_v1.pdf`
  - `Support_Premium_Reecriture_URL_Apache_Client4_GRETA_v2.pdf`
- Plusieurs documents sont transversaux et doivent vivre dans plusieurs parcours.

## Structure d'information normalisée
- Niveau 1 : Learning Paths
- Niveau 2 : Modules
- Niveau 3 : Leçons
- Niveau 4 : Ressources
- Couche transverse : concepts, outils, tags, niveau, statut

## Learning Paths retenus
1. Fondamentaux Linux
2. Réseau Linux & DNS
3. Stack LAMP & culture base de données
4. Administration MariaDB
5. Modélisation & SQL MariaDB
6. TPs MariaDB
7. Apache2 : déploiement & multisites
8. Apache2 : sécurité, `.htaccess` & URL rewriting
9. Développement PHP
10. WordPress sur Apache/PHP/MariaDB
11. Nginx & PHP-FPM

## Règle spécifique pour la section PHP
- La section PHP doit être visible dès la homepage et dans la navigation principale.
- Elle mélange contenu sourcé et structure future-ready.
- Les modules à prévoir même si la matière est partielle :
  - introduction à PHP
  - syntaxe, variables, conditions, boucles
  - tableaux et fonctions
  - formulaires et superglobales
  - sessions et cookies
  - fichiers
  - accès MariaDB / PDO
  - validation et sanitization
  - authentification
  - pratiques sécurisées
  - PHP dans WordPress

## Modèle de données WordPress
### Custom post types
- `learning_path`
- `learning_module`
- `learning_lesson`
- `learning_resource`

### Taxonomies
- `learning_domain`
- `learning_level`
- `resource_format`
- `content_status`
- `stack_area`

### Relations fonctionnelles
- un `learning_path` contient plusieurs `learning_module`
- un `learning_module` contient plusieurs `learning_lesson`
- une `learning_lesson` relie plusieurs `learning_resource`
- une `learning_resource` peut appartenir à plusieurs domaines

## Architecture WordPress
- WordPress = socle CMS et admin éditorial
- Thème custom = expérience frontend premium
- Plugin custom = modèle éditorial, taxonomies, inventaire, widgets, helpers
- Pas de dépendance lourde à un page builder
- Priorité au rendu serveur
- Alpine.js uniquement pour les interactions légères

## Structure du thème
- `functions.php` : bootstrapping thème, scripts, menus, support features
- `front-page.php` : homepage premium et narration double identité
- `archive-learning_path.php` : archive des parcours
- `single-learning_path.php` : détail parcours
- `single-learning_module.php` : détail module
- `single-learning_lesson.php` : détail leçon
- `single-learning_resource.php` : fiche ressource + accès PDF
- `page-dashboard.php` : vue tableau de bord
- `page-resource-library.php` : bibliothèque documentaire
- `template-parts/` : hero, path cards, resource cards, stack callouts
- `inc/` : helpers de rendu, lecture du catalogue JSON

## Structure du plugin
- enregistrement des CPT et taxonomies
- helpers d'inventaire
- API de fallback pour la donnée source
- widgets/admin page pour visualiser le corpus
- fonctions utilitaires pour métriques et taxonomie

## Typologie de pages
- Home
- Archive Learning Paths
- Single Learning Path
- Single Module
- Single Lesson
- Single Resource
- Resource Library
- Dashboard
- About / Built with the Course Stack

## Inventaire de composants
- hero plein écran avec image immersive
- bandeau "Built with the technologies studied during the training"
- stack chips
- cartes parcours
- cartes modules
- cartes leçons
- cartes ressources PDF
- cartes de séquence module / lesson
- callouts architecture
- section "Pourquoi ce projet existe"
- métriques de progression
- tableau de bord éditorial avec santé du corpus
- filtres de bibliothèque via Alpine
- preview statique multi-pages pour inspection hors runtime PHP

## Règles de design
- esthétique premium, nette, sérieuse
- pas de sensation de thème WordPress générique
- pas de section encadrée comme preview décorative pour l'expérience principale
- rayons d'arrondi limités
- hiérarchie typographique forte
- contraste élevé
- grilles stables
- éviter les palettes monotones violettes, beiges, bleu sombre dominantes

## Règles de visibilité de stack
- Le hero doit afficher explicitement :
  - WordPress
  - PHP
  - MariaDB
  - Apache2
  - Tailwind CSS
  - Alpine.js
  - HTML
- La homepage doit contenir une section éditoriale expliquant le lien entre le cours et la plateforme.
- Le dashboard doit rappeler que la plateforme est une démonstration technique.
- Le footer doit assumer le stack comme élément identitaire, pas comme note annexe.

## Exigences du Hero
- Titre officiel complet : GRETA ANALYSTE CYBERSÉCURITÉ
- Sous-texte clair sur la double mission
- CTA vers les parcours
- CTA vers la section stack appliquée
- image immersive orientée workstation / serveur / cybersécurité
- badges de stack visibles immédiatement
- métriques du corpus visibles

## Sécurité
- échapper toutes les sorties
- sanitization des entrées
- usage préférentiel des APIs WordPress
- pas de SQL inline non préparé
- séparation claire entre configuration et code
- secrets uniquement via variables d'environnement

## Accessibilité
- contraste AA minimum
- focus visibles
- hiérarchie sémantique correcte
- labels explicites
- filtres utilisables au clavier
- textes courts et lisibles

## Performance
- éviter les scripts lourds
- privilégier le HTML rendu serveur
- limiter les images
- compresser la couche documentaire en métadonnées plutôt qu'en duplication de contenu
- garder les PDF servis directement depuis le stockage du projet / uploads

## Variables d'environnement prévues
- `WORDPRESS_DB_HOST`
- `WORDPRESS_DB_NAME`
- `WORDPRESS_DB_USER`
- `WORDPRESS_DB_PASSWORD`
- `WORDPRESS_TABLE_PREFIX`
- `WORDPRESS_DEBUG`
- `WORDPRESS_CONFIG_EXTRA`
- `WP_HOME`
- `WP_SITEURL`

## Stratégie de déploiement
- Dockerfile racine pour Railway
- image WordPress Apache/PHP
- copie du thème et du plugin dans l'image
- service Railway applicatif connecté à un service MariaDB séparé
- config WordPress pilotée par variables Railway

## Décisions prises
1. Ne pas attendre un code existant : partir du corpus documentaire réel.
2. Utiliser WordPress comme CMS, pas comme thème générique.
3. Créer un plugin custom pour le modèle pédagogique.
4. Assumer une première verticale avec fallback data pour rester productive sans runtime PHP local.
5. Faire de la section PHP un parcours visible et sérieux même quand une partie du contenu est encore seedée.
6. Exposer les variantes documentaires plutôt que les masquer afin de garder la traçabilité du corpus source.
7. Donner aux pages module et lesson une navigation séquentielle pour éviter un sentiment de cul-de-sac.

## Risques
- Pas de runtime PHP local disponible dans cet environnement de travail.
- Pas de WordPress core local à vérifier.
- Tailwind sera d'abord injecté via CDN pour la première verticale.
- Le corpus est très riche mais parfois redondant ; la normalisation doit rester lisible.

## Checklist de vérification
- inventaire source documenté
- double identité visible
- hero explicite
- parcours normalisés
- section PHP présente
- WordPress theme/plugin scaffoldés
- ressource PDF card présente
- déploiement Railway documenté

## Pending work
- enrichir encore les pages module / lesson avec contenu plus finement extrait
- importer automatiquement les ressources dans WordPress
- remplacer la couche Tailwind CDN par build local si runtime disponible
- ajouter recherche plus avancée et filtres multi-taxonomies
- brancher un vrai tableau de progression utilisateur
- synchroniser le dashboard avec des données WordPress réelles après validation du runtime
