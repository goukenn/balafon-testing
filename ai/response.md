Summary: What is a Balafon's Project?

  A Balafon Project is a standardized, self-contained PHP web application module built on the Balafon framework. Here's what defines it:

  Core Characteristics:

  - MVC Architecture - Controllers, Views, and Models following strict separation
  - Central Metadata - Every project has a balafon.config.json file defining its identity and dependencies
  - Main Entry Point - A {ProjectName}Controller.php class that handles requests
  - Standard Directory Structure - Consistent layout across all 53 projects

  Key Components:

  1. Backend - PHP classes in Lib/, database schemas in data.schema.xml
  2. Frontend - .phtml template views with shared header/footer/menu includes
  3. Styling - PCSS format with theme support and responsive breakpoints
  4. Configuration - Routes, language files, profiles all in Configs/
  5. Assets - Compiled CSS/JS, images in Data/assets/
  6. Modern Stack - Vite/Webpack for bundling, Vue.js for components

  Multilingual & Database:

  - Built-in language support (English, French, Dutch)
  - XML-based database schema definitions
  - Asset pipeline with source separation

  Project Types in Your Repository:

  - Full web applications (CarRental, Tonerafrika)
  - Documentation/Wikis (BalafonWikiAI, BcssWiki)
  - Tutorials & Demos (DemoVueApp, BalafonProjectTutorial)
  - Utilities & Tools (qrgenerator, ChatGpt, MailPreviewer)
  - Admin systems (SchoolManager, ForemJobDashboard)

  Each project is modular, reusable, and can depend on other shared libraries through the Balafon dependency system.

  ---


  Résumé : Qu'est-ce qu'un Projet Balafon ?

  Un Projet Balafon est un module d'application web PHP standardisé et autonome construit sur le cadre Balafon. Voici ce qui le définit :

  Caractéristiques Principales :

  - Architecture MVC - Contrôleurs, Vues et Modèles suivant une séparation stricte
  - Métadonnées Centrales - Chaque projet a un fichier balafon.config.json définissant son identité et ses dépendances
  - Point d'Entrée Principal - Une classe {ProjectName}Controller.php qui gère les requêtes
  - Structure de Répertoires Standardisée - Disposition cohérente dans les 53 projets

  Composants Clés :

  1. Backend - Classes PHP dans Lib/, schémas de base de données dans data.schema.xml
  2. Frontend - Vues de modèles .phtml avec en-têtes/pieds de page/menus partagés
  3. Style - Format PCSS avec support de thèmes et points d'arrêt réactifs
  4. Configuration - Routes, fichiers de langue, profils tous dans Configs/
  5. Actifs - CSS/JS compilés, images dans Data/assets/
  6. Stack Moderne - Vite/Webpack pour le bundling, Vue.js pour les composants

  Multilingue & Base de Données :

  - Support de langue intégré (anglais, français, néerlandais)
  - Définitions de schéma de base de données basées sur XML
  - Pipeline d'actifs avec séparation source

  Types de Projets dans Votre Référentiel :

  - Applications web complètes (CarRental, Tonerafrika)
  - Documentations/Wikis (BalafonWikiAI, BcssWiki)
  - Tutoriels & Démos (DemoVueApp, BalafonProjectTutorial)
  - Utilitaires & Outils (qrgenerator, ChatGpt, MailPreviewer)
  - Systèmes d'administration (SchoolManager, ForemJobDashboard)

  Chaque projet est modulaire, réutilisable et peut dépendre d'autres bibliothèques partagées via le système de dépendances Balafon.

  ---
  Analyse Détaillée : Structure d'un Projet Balafon

  APERÇU

  Il y a 53 projets dans le référentiel du cadre Balafon. Le dossier Projects sert de centre névralgique pour divers types d'applications web et d'utilitaires construits sur le cadre PHP Balafon - un cadre MVC moderne avec thème dynamique, support multilingue et capacités de base de données.

  ---
  ## CATÉGORIES DE PROJETS AU NIVEAU SUPÉRIEUR

  Les projets se divisent en plusieurs catégories :

  1. Applications Web Complètes (p. ex., CarRental, Tonerafrika, L81, Ttre)
  2. Projets Wiki/Documentation (p. ex., BalafonWikiAI, BalafonWikiDocs, BcssWiki)
  3. Projets Démo/Tutoriel (p. ex., DemoVueApp, WatchCssDemo, TailwindcssDemo, BalafonProjectTutorial)
  4. Projets Utilitaire/Outil (p. ex., qrgenerator, MailPreviewer, ChatGpt, AppBalafon)
  5. Projets Admin/Gestion (p. ex., ForemJobDashboard, SchoolManager)
  6. Projets Éducatifs (p. ex., PythonForDataSciences, AppFaqs)
  7. Projets de Base du Cadre (p. ex., igk_default, igk_bondje, Schemas)
  8. Projets Cachés Spéciaux (p. ex., .BalafonWikiController, .removed)

  ---
  STRUCTURE DE PROJET STANDARD

  Chaque projet Balafon suit une disposition de répertoire cohérente :

  ProjectName/
  ├── Articles/                    # Pages de contenu statique (support multilingue)
  ├── Configs/                     # Fichiers de configuration
  │   ├── routes.php              # Définitions de routage URL
  │   ├── views.php               # Configuration des vues
  │   ├── profiles.php            # Profils utilisateur & authentification
  │   ├── vue-route.php           # Routage Vue (le cas échéant)
  │   └── Lang/                   # Fichiers de langue multilingues
  ├── Contents/                   # Fichiers de contenu
  ├── Data/                       # Stockage de données, actifs et fichiers générés
  │   ├── assets/                 # Actifs statiques (CSS, JS, images, etc.)
  │   │   ├── css/               # Feuilles de style compilées
  │   │   ├── js/                # Bundles JavaScript
  │   │   ├── img/               # Images
  │   │   └── [module-specific]/ # Sorties de construction
  │   ├── store/                  # Stockage de fichiers
  │   ├── Docs/                   # Documentation
  │   └── [schema].schema.xml     # Définitions du schéma de base de données
  ├── Lib/                        # Classes PHP et utilitaires
  │   ├── Classes/                # Classes PHP avec structure d'espace de noms
  │   ├── Tests/                  # Tests PHPUnit
  │   └── autoload.php           # Chargeur automatique PHP
  ├── Scripts/                    # Fichiers source JavaScript/TypeScript
  ├── Styles/                     # Fichiers de prétraitement CSS (format .pcss)
  │   └── Themes/                # Variations de thème (light.theme.pcss, dark.theme.pcss)
  ├── Views/                      # Modèles de vue PHP (format .phtml)
  │   ├── .header.pinc           # En-tête partagé inclus
  │   ├── .footer.pinc           # Pied de page partagé inclus
  │   ├── .menu.pinc             # Configuration du menu de navigation
  │   ├── default.phtml          # Modèle de vue principal
  │   └── [action].phtml         # Vues spécifiques aux actions
  ├── VueComponents/              # Fichiers de composant Vue.js (format .vue)
  ├── ViewLayout/                 # Modèles de disposition de vue (certains projets)
  ├── [ProjectName]Controller.php # Contrôleur principal (étend ApplicationController)
  ├── balafon.config.json        # Métadonnées et configuration du projet
  ├── package.json               # Dépendances Node.js (si utilisation de npm/yarn)
  ├── vite.config.js             # Configuration de construction Vite (projets modernes)
  ├── webpack.config.js          # Configuration Webpack (projets hérités)
  ├── tailwind.config.js         # Configuration Tailwind CSS (si utilisation de Tailwind)
  ├── postcss.config.js          # Configuration PostCSS
  ├── phpunit.xml.dist           # Configuration de test PHPUnit
  ├── phpunit-watcher.yml        # Configuration du mode surveillance PHPUnit
  ├── .global.php                # Fonctions globales et constantes
  ├── .db.constants.php          # Constantes de base de données
  ├── .balafon-sync.project.json # Métadonnées de synchronisation du projet
  ├── .gitignore                 # Règles d'ignorance Git
  ├── README.md                  # Documentation du projet
  └── yarn.lock / package-lock.json # Fichiers de verrouillage des dépendances

---
DÉFINITIONS DES FICHIERS CRITIQUES

1. balafon.config.json (Fichier le Plus Important)

Le fichier de métadonnées du projet principal. Tous les projets l'ont. La structure varie selon le type de projet :

Configuration minimale :
```json
{
    "name": "ProjectName",
    "author": "C.A.D. BONDJE DOUE",
    "version": "1.0"
}
```

Configuration étendue (avec dépendances) :
```json
{
"name": "ProjectName",
"version": "1.0",
"author": "C.A.D. BONDJE DOUE",
"description": "Description du projet",
"required": {
    "igk/ecommerce": "1.0",
    "igk/js/Vue3": "1.0",
    "igk/bootstrap": "1.0"
}
}
```

Avec projets Vite :
```json
{
"name": "ProjectName",
"viteProjects": {
    "project-name": {
    "author": "Author",
    "date": "20230101 00:00:00",
    "version": "1.0",
    "location": "Data/VueApp/project-name",
    "dist": "Data/assets/project-name/dist"
    }
}
}
```

2. [ProjectName]Controller.php

- Point d'entrée principal de la classe PHP
- Étend IGK\Controllers\ApplicationController
- Implémentation minimale dans la plupart des cas
- Exemple :
<?php
use IGK\Controllers\ApplicationController;

class CarRentalController extends ApplicationController {
}

3. Configs/routes.php

- Définit les modèles de routage URL
- Utilise des définitions de Route comme Route::get($actionClass, "/path/{param}")
- Supporte les paramètres obligatoires et optionnels

4. Configs/views.php

- Configuration des vues et paramètres de répertoire
- Définitions du répertoire d'entrée

5. Configs/profiles.php

- Définitions du profil utilisateur
- Groupes d'authentification

6. Configs/Lang/ (Fichiers de Langue)

- Fichiers .en.presx, .fr.presx, .nl.presx
- Chaînes de traduction pour l'internationalisation
- Accessibles en code via $l["key"]

7. Répertoire Views/ (Système de Modèles)

- .header.pinc - En-tête partagé avec configuration HTML
- .footer.pinc - Pied de page partagé
- .menu.pinc - Configuration du menu de navigation
- default.phtml - Répartiteur de modèle principal
- Vues d'actions comme {actionName}.phtml
- Fichiers .bview (format de vue alternatif dans certains projets)

8. Répertoire Styles/ (Fichiers CSS/PCSS)

- Format .pcss (CSS amélioré par PHP avec support des variables)
- default.pcss - Feuille de style principale
- Sous-répertoire Themes/ avec variations de thème
- Support Tailwind CSS et traitement PostCSS
- Variables : $cl (couleurs), $def (par défaut)
- Points d'arrêt réactifs : $xsm_screen, $sm_screen, $lg_screen

9. Répertoire Lib/

- Classes/ - Classes PHP avec modèle d'espace de noms : com\igkdev\projects\{ProjectName}\{SubNamespace}
- Tests/ - Fichiers de test PHPUnit étendant ControllerBaseTestCase
- autoload.php - Chargeur automatique PSR-4

10. Répertoire Data/

- assets/ - CSS, JS, images compilés, etc.
- data.schema.xml - Définitions du schéma de base de données
- config.xml - Configuration des données
- store/ - Sous-répertoire de stockage de fichiers
- thumbs/ - Images miniatures générées

11. Répertoire Articles/

- Pages de contenu statique (format .phtml)
- Support de nommage multilingue : {name}.{lang}.phtml
- Exemples : about.en.phtml, about.fr.phtml, about.nl.phtml

12. Répertoire VueComponents/

- Fichiers de composant Vue 3 (format .vue)
- Composants à fichier unique avec modèle, script, style

13. Répertoire Scripts/

- Fichiers source JavaScript/TypeScript
- Scripts d'application spécifiques au projet
- main.js - Point d'entrée
- .app.js - Initialisation de l'application
- .global.js - Fonctions JavaScript globales

---
FICHIERS DE CONFIGURATION

Fichiers Système de Construction/Node.js :

- package.json - Dépendances NPM/Yarn (12 projets l'ont)
- yarn.lock - Fichier de verrouillage des dépendances Yarn
- package-lock.json - Fichier de verrouillage des dépendances NPM

Outils de Construction :

- vite.config.js - Configuration du bundler Vite (projets modernes : DemoVueApp, SampleVueAppDemo, AppFaqs)
- webpack.config.js - Configuration du bundler Webpack (projets hérités)
- tailwind.config.js - Configuration Tailwind CSS
- postcss.config.js - Configuration de transformation PostCSS
- bformatter.config.js - Configuration du formateur de code

Test & Développement :

- phpunit.xml.dist - Configuration de test PHPUnit avec configuration d'environnement
- phpunit-watcher.yml - Configuration du mode surveillance PHPUnit
- .eslintrc.json - Configuration de linting JavaScript (certains projets)
- .babelrc - Configuration du transpileur JavaScript Babel

Fichiers Spéciaux :

- .global.php - Fonctions globales, constantes et initialisation
- .db.constants.php - Constantes de connexion à la base de données
- .balafon-sync.project.json - Métadonnées de synchronisation du projet (53 octets, minimal)
- .gitignore - Règles d'ignorance Git
- composer.json - Configuration du compositeur PHP (certains projets)
- CLAUDE.md - Documentation d'orientation Claude Code AI (trouvée dans BalafonProjectTutorial)

---
MODÈLES ET CONVENTIONS COMMUNS

1. Conventions de Nommage de Fichiers :

- Contrôleurs : {ProjectName}Controller.php (PascalCase)
- Classes : {ClassName}.php (PascalCase)
- Vues : {actionname}.phtml (minuscules)
- Includes partagées : .{name}.pinc (point initial)
- Thèmes : {name}.theme.pcss
- Multilingue : {name}.{lang}.phtml (p. ex., .en, .fr, .nl)
- Configs protégées : Configs/, Lib/, Contents/ ont .htaccess avec deny from all
- Répertoires publics : Scripts/, Data/, Styles/, Views/ ont accès public

2. Structure de Base de Données :

- Les fichiers data.schema.xml définissent les schémas de base de données
- Les classes de migration dans Lib/Classes/Database/ avec méthodes upgrade() et downgrade()
- Les classes de base de données suivent l'espace de noms com\igkdev\projects\{Project}\Database

3. Système de Langue/Traduction :

- Support multilingue avec fichiers .presx (anglais, français, néerlandais)
- Les tableaux de langue sont accessibles sous la forme $l["key"] dans les vues
- Les articles supportent les variantes de langue : article.en.phtml, article.fr.phtml

4. Architecture des Vues :

- Rendu basé sur modèle avec fichiers .phtml
- Includes partagées pour en-têtes, pieds de page, menus
- Sélection dynamique des vues basée sur le routage
- Variables disponibles dans les vues : $t, $ctrl, $doc, $dir, $fname, $l

5. Système de Style :

- PCSS (CSS amélioré par PHP) avec support des variables
- Pipeline de traitement PostCSS
- Intégration optionnelle de Tailwind CSS
- Capacité de commutation de thème
- Points d'arrêt de conception réactive

6. Stack JavaScript Moderne :

- Intégration Vue 3 dans plusieurs projets
- Bundler Vite pour les nouveaux projets
- Webpack pour les anciens projets
- Composants Vue à fichier unique

---
STATISTIQUES DU PROJET

- Projets Totaux : 53
- Projets avec balafon.config.json : 50
- Projets avec package.json : 12 (projets Node.js)
- Projets avec section viteProjects : 4 (CarRental, Ttre, BalafonWikiAI, app_test)
- Projets avec config Vite : 3 (DemoVueApp, SampleVueAppDemo, AppFaqs)
- Projets avec dépendances définies : 12

---
TYPES DE PROJETS SPÉCIAUX

1. Projets du Noyau du Cadre (igk_default, igk_bondje, Schemas)
- Servent de bibliothèques de base pour d'autres projets
2. Projets Documentation/Wiki (BalafonWikiAI, BalafonWikiDocs, BcssWiki, BalafonWikiController)
- Matériaux de documentation et de référence
3. Projets Tutoriel (BalafonProjectTutorial, DemoVueApp)
- Code éducatif et d'exemple
4. Projets de Test (app_test)
- Fonctionnalités expérimentales et terrain d'essai
5. Applications Complètes (CarRental, Ttre, L81, Tonerafrika)
- Applications professionnelles complètes avec schémas de base de données, vues multiples et logique complexe

---
OBSERVATIONS CLÉS

1. Tous les projets suivent une architecture MVC stricte avec séparation claire des préoccupations
2. Chaque projet a un Contrôleur dédié qui sert de point d'entrée
3. Le balafon.config.json est universel et essentiel pour l'identification du projet
4. Les projets modernes utilisent de plus en plus Vite comme outil de construction
5. La plupart des projets supportent plusieurs langues (anglais, français, néerlandais)
6. Les schémas de base de données sont stockés au format XML (data.schema.xml)
7. Les référentiels Git sont initialisés dans les projets individuels (répertoires .git)
8. Utilisation extensive de bibliothèques partagées et de dépendances via le champ required dans balafon.config.json
9. Le pipeline d'actifs sépare les fichiers sources (Scripts, Styles) des actifs construits (Data/assets)
10. Le système de vues utilise les includes PHP pour les composants partagés (en-tête, pied de page, menu)