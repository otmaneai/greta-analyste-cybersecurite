# Runtime Validation Status

## Current state
- Structure Docker et Railway prête
- Validation d'inventaire réussie
- Validation runtime **non exécutée dans ce workspace**

## Blockers in this machine
- `docker` absent
- `php` absent
- `composer` absent
- pas de runtime WordPress local exécutable

## Required runtime checks once Docker is available
1. `docker compose up --build`
2. ouvrir `http://localhost:8080`
3. finaliser l'installation WordPress
4. activer le thème `GRETA Analyste Cybersecurite`
5. activer le plugin `GRETA Learning Platform`
6. vérifier :
   - homepage
   - archive learning paths
   - single learning path
   - single module
   - single lesson
   - single resource
   - resource library
   - dashboard
7. vérifier les PDF dans `wp-content/uploads/greta-resources`

## Acceptance criteria
- le plugin seed les contenus sans erreur fatale
- les permaliens résolvent les contenus
- les ressources ouvrent leur PDF
- le dashboard rend les métriques et états du corpus
- la stack visible reste cohérente entre home, dashboard et parcours PHP
