# Documentation Complète - Syntaxe des Fichiers .bview

**Date:** 29 Décembre 2025  
**Version:** 1.0  
**Auteur:** Claude AI Assistant

---

## Table des Matières

1. [Vue d'ensemble](#vue-densemble)
2. [Structure de base](#structure-de-base)
3. [Syntaxe des nœuds](#syntaxe-des-nœuds)
4. [Éléments et classes](#éléments-et-classes)
5. [Attributs et arguments](#attributs-et-arguments)
6. [Contenu textuel](#contenu-textuel)
7. [Séparation des nœuds](#séparation-des-nœuds)
8. [Variables et interpolation](#variables-et-interpolation)
9. [Commentaires](#commentaires)
10. [Exemples pratiques](#exemples-pratiques)
11. [Bonnes pratiques](#bonnes-pratiques)

---

## Vue d'ensemble

Les fichiers `.bview` (Balafon View) sont des fichiers de vue déclaratifs du framework Balafon. Ils permettent de définir la structure HTML sans code PHP complexe.

**Caractéristiques principales:**
- Syntaxe concise et lisible
- Séparation claire entre structure et styles
- Support complet de l'interpolation de variables
- Accès au contexte et au contrôleur
- Génération optimisée du HTML

---

## Structure de base

### Format général d'un nœud

```
element.classe1.classe2#id[attribut:valeur]{ contenu }
```

### Composants d'un nœud

| Élément | Syntaxe | Obligatoire | Description |
|---------|---------|-------------|-------------|
| **Élément** | `div`, `span`, `button` | ✓ Oui | Balise HTML |
| **Classes** | `.classe1.classe2` | ✗ Non | Classes CSS (préfixe `.`) |
| **ID** | `#identifiant` | ✗ Non | Identifiant unique (préfixe `#`) |
| **Name** | `@attribut` | ✗ Non | Attribut name (préfixe `@`) |
| **Attributs** | `[clé:valeur]` | ✗ Non | Attributs HTML/SVG (crochets `[]`) |
| **Contenu** | `{ ... }` | ✗ Non | Contenu du nœud (accolades `{}`) |

---

## Syntaxe des nœuds

### 1. Élément simple

```bview
div
```

**HTML généré:**
```html
<div></div>
```

### 2. Élément avec classes

```bview
div.container
div.container.large.centered
```

**HTML généré:**
```html
<div class="container"></div>
<div class="container large centered"></div>
```

### 3. Élément avec ID

```bview
div#main
div#header.container
```

**HTML généré:**
```html
<div id="main"></div>
<div class="container" id="header"></div>
```

### 4. Élément avec attribut name

```bview
input@email
input@password.form-control
```

**HTML généré:**
```html
<input name="email">
<input name="password" class="form-control">
```

---

## Éléments et classes

### Ordre de déclaration

La syntaxe doit respecter cet ordre:
1. Élément (obligatoire)
2. Classes (préfixe `.`)
3. ID (préfixe `#`)
4. Name (préfixe `@`)
5. Attributs (crochets `[]`)
6. Contenu (accolades `{}`)

```bview
/* ✓ Correct */
button.btn.btn-primary#submit@action[data-action:submit]{ - Envoyer }

/* ✗ Incorrect - ordre invalide */
button#submit.btn[data:action]{ - Envoyer }.btn-primary
```

### Plusieurs classes

```bview
div.container.main.content
```

**HTML généré:**
```html
<div class="container main content"></div>
```

---

## Attributs et arguments

### Attributs HTML/SVG avec `[]`

Les attributs HTML/SVG sont placés entre crochets `[]` avec le séparateur `:` (sans guillemets).

```bview
/* Syntaxe simple */
a[href:url, title:Lien]

/* HTML généré */
<a href="url" title="Lien"></a>
```

### Bind global avec `*`

Le préfixe `*` permet de créer un bind global sur un attribut:

```bview
/* Syntaxe */
input[*type:text]
div[*data-id:123]

/* Crée un binding bidirectionnel sur l'attribut */
```

**Attribut avec bind global:**

```bview
input[*value:{{ $name }}]
select[*data-selection:{{ $selected }}]
```

### Format des attributs

```bview
/* Attribut simple */
input[type:text]

/* Plusieurs attributs */
input[type:email, placeholder:Email, required:true]

/* Attributs avec tiret */
div[data-id:123, aria-label:Menu]

/* Sans guillemets, avec deux-points comme séparateur */
a[href:google.com, title:Lien Google]
```

### Attributs vides

Si aucun attribut n'est nécessaire, omettez simplement les crochets :

```bview
a[href:google.com]{ - Google }
div{ - Contenu }
```

---

## Contenu textuel

### Préfixe `-` pour le contenu

Le préfixe `-` indique toujours du contenu textuel.

```bview
p{ - Texte simple }
h1{ - Titre principal }
span{ - Contenu court }
```

**HTML généré:**
```html
<p>Texte simple</p>
<h1>Titre principal</h1>
<span>Contenu court</span>
```

### Contenu multilignes

```bview
div{
    - Texte sur plusieurs
      lignes avec indentation
      préservée
}
```

### Interpolation avec variables

L'interpolation de variables se fait directement après le `-` sans backticks:

```bview
p{ - Bonjour {{ $name }} }
span{ - Prix: {{ $price }}€ }
h1{ - {{ $title }} }
```

---

## Séparation des nœuds

### Nœuds avec contenu `{}`

**Pas de virgule nécessaire** - les nœuds avec accolades sont séparés par l'espace ou les retours à la ligne.

```bview
div{
    h1{ - Titre }
    p{ - Paragraphe }
    span{ - Texte }
}
```

**HTML généré:**
```html
<div>
    <h1>Titre</h1>
    <p>Paragraphe</p>
    <span>Texte</span>
</div>
```

### Nœuds sans contenu

**Virgule obligatoire `,`** - pour séparer les nœuds sans contenu (auto-fermants).

```bview
nav{
    input@search[type:text],
    button[aria-label:Search],
    select@sort
}
```

**HTML généré:**
```html
<nav>
    <input name="search" type="text">
    <button aria-label="Search"></button>
    <select name="sort"></select>
</nav>
```

### Nœuds mixtes

Mélange de nœuds avec et sans contenu:

```bview
header{
    h1{ - Logo }
    input@search[type:text],
    button[aria-label:Menu]
}
```

### Règles de séparation

| Situation | Séparation | Exemple |
|-----------|-----------|---------|
| Nœud avec `{}` suivi d'un nœud avec `{}` | Espace/retour ligne | `div{ - A } div{ - B }` |
| Nœud sans contenu suivi d'un nœud sans contenu | Virgule `,` | `input, button` |
| Nœud avec `{}` suivi d'un nœud sans contenu | Virgule `,` | `div{ - A }, input` |
| Nœud sans contenu suivi d'un nœud avec `{}` | Virgule `,` | `input, div{ - B }` |
| Dernier nœud avant `}` fermant | Pas de séparation | `div{ ... item }` |

---

## Variables et interpolation

### Syntaxe d'interpolation

Utilisez `{{ $variable }}` pour insérer des variables dans le contenu.

```bview
p{ - {{ $name }} }
h2{ - {{ $title }} }
span{ - {{ $value }} }
```

### Variables disponibles

Deux objets sont toujours accessibles:

#### `$raw` - Données brutes

```bview
p{ - {{ $raw->email }} }
p{ - {{ $raw->city ?? 'Non spécifiée' }} }
```

#### `$ctrl` - Contrôleur

```bview
a[href:{{ $ctrl->uri('/home') }}]{ - Accueil }
```

### Raccourci - Variables directes

Les variables de `$raw` sont directement accessibles:

```bview
/* Équivalent */
{{ $name }}     →     {{ $raw->name }}
{{ $email }}    →     {{ $raw->email }}
```

### Expressions

```bview
p{ - {{ $price * $quantity }}€ }
span.{{ $active ? 'success' : 'danger' }}{ - Statut }
p{ - {{ $city ?? 'Inconnue' }} }
```

### Accès aux propriétés

```bview
/* Propriété d'objet */
p{ - {{ $user->name }} }

/* Tableau */
p{ - {{ $items[0] }} }

/* Imbriqué */
p{ - {{ $user->address->country }} }
```

### Filtres avec pipe `|`

```bview
p{ - `{{ $text | uppercase }}` }
p{ - `{{ $name | lowercase }}` }
```

---

## Commentaires

### Commentaires sur une ligne

```bview
/* Ceci est un commentaire */
div{ - Contenu }
```

### Commentaires multilignes

```bview
/*
 * Commentaire sur
 * plusieurs lignes
 */
div{ - Contenu }
```

### Directives (métadonnées)

Les directives sont des métadonnées placées en début de fichier avec la syntaxe `# @nom valeur`:

```bview
# @description Page de gestion des credentials personnels
# @author Claude AI Assistant
# @date 28 December 2025
# @version 1.0

main.main-layout{
    /* ... */
}
```

**Directives courantes:**

| Directive | Syntaxe | Exemple |
|-----------|---------|---------|
| **Description** | `# @description` | `# @description Page d'accueil` |
| **Auteur** | `# @author` | `# @author Claude AI Assistant` |
| **Date** | `# @date` | `# @date 28 December 2025` |
| **Version** | `# @version` | `# @version 1.0` |
| **Namespace** | `# @namespace` | `# @namespace components` |

---

## Exemples pratiques

### Exemple 1: Formulaire simple

```bview
form#contact-form.form{
    div.form-group{
        label[for:name]{ - Nom }
        input#name@name[type:text, required:true]
    }
    div.form-group{
        label[for:email]{ - Email }
        input#email@email[type:email, required:true]
    }
    button.btn.btn-primary[type:submit]{ - Envoyer }
}
```

### Exemple 2: Carte de produit

```bview
article.product-card[data-id:{{ $id }}]{
    img[src:{{ $image }}, alt:{{ $name }}]
    h3.product-title{ - {{ $name }} }
    p.product-price{ - {{ $price }}€ }
    button.btn[aria-label:Ajouter]{ - Ajouter au panier },
    button.btn.btn-secondary{ - Détails }
}
```

### Exemple 3: Navigation avec onglets

```bview
nav.tab-navigation{
    a.tab-link[href:#workflows]{ - Workflows }
    a.tab-link.tab-active[href:#credentials]{ - Credentials }
    a.tab-link[href:#executions]{ - Executions }
    a.tab-link[href:#data-tables]{ - Data tables }
}
```

### Exemple 4: Barre de recherche

```bview
div.search-bar{
    input.search-box@search[type:text, placeholder:Rechercher...],
    select.sort-control@sort,
    button.btn-filter[aria-label:Filtres]
}
```

### Exemple 5: Liste avec conditions

```bview
ul.items-list{
    li.item.{{ $active ? 'active' : 'inactive' }}{
        - {{ $name }}
    }
}
```

---

## Bonnes pratiques

### ✓ À FAIRE

```bview
/* 1. Indentation cohérente */
main.container{
    section.hero{
        h1{ - Titre }
    }
}

/* 2. Grouper logiquement les nœuds */
div.header{
    h1{ - Logo }
    nav{ /* navigation */ }
    button[aria-label:Menu]
}

/* 3. Utiliser des noms de classes descriptifs */
div.credential-card[data-id:1]{
    div.card-header{ /* ... */ }
    div.card-body{ /* ... */ }
}

/* 4. Commenter les sections importantes */
/* Navigation Tabs */
nav.tab-navigation{ /* ... */ }

/* 5. Pas de backticks après le tiret */
p{ - Bonjour {{ $name }} }
span{ - Texte {{ $value }} avec interpolation }
h1{ - {{ $title }} }
```

### ✗ À ÉVITER

```bview
/* 1. Indentation incohérente */
main.container{
section.hero{
h1{ - Titre }
}}

/* 2. Mélanger les styles de séparation */
div{
    h1{ - A }, p{ - B }
}

/* 3. Noms vagues ou trop génériques */
div.container1{ }
div.section2{ }

/* 4. Interpolation sans backticks */
p{ - Bonjour {{ $name }} }  /* Pas de backticks */

/* 5. Oublier les virgules entre nœuds sans contenu */
div{
    input[type:text]    /* ✗ Manque virgule */
    button
}
```

### Organisation des fichiers

```
Views/
├── layouts/
│   ├── main.bview
│   └── admin.bview
├── pages/
│   ├── home.bview
│   ├── about.bview
│   └── contact.bview
└── components/
    ├── header.bview
    ├── footer.bview
    └── sidebar.bview

Styles/
├── layouts/
│   ├── main.pcss
│   └── admin.pcss
├── pages/
│   ├── home.pcss
│   ├── about.pcss
│   └── contact.pcss
└── components/
    ├── header.pcss
    ├── footer.pcss
    └── sidebar.pcss
```

### Nommage des fichiers

- **Pages:** `kebab-case` (ex: `about-us.bview`)
- **Composants:** `kebab-case` (ex: `user-card.bview`)
- **Layouts:** `nom-layout.bview` (ex: `main-layout.bview`)

### Documentation des variables

```bview
/*
 * credentials-card.bview
 * 
 * Variables attendues:
 * - $id (int) : ID du credential
 * - $name (string) : Nom du credential
 * - $provider (string) : Fournisseur (ex: OpenAI, AWS)
 * - $lastUpdated (string) : Date de dernière mise à jour
 */

article.credential-card[data-id:{{ $id }}]{
    h3{ - {{ $name }} }
    p{ - {{ $provider }} }
    small{ - Mis à jour: {{ $lastUpdated }} }
}
```

---

## Résumé rapide

| Feature | Syntaxe | Exemple |
|---------|---------|---------|
| **Élément** | `tagname` | `div` |
| **Classe** | `.class` | `.container.large` |
| **ID** | `#id` | `#main` |
| **Name** | `@name` | `@email` |
| **Attribut** | `[clé:valeur]` | `[href:#home, title:Accueil]` |
| **Contenu** | `{ - texte }` | `{ - Contenu }` |
| **Variable** | `{{ $var }}` | `{{ $name }}` |
| **Séparation** | `,` ou espace | `input, button` ou `div{ } div{ }` |
| **Commentaire** | `/* ... */` | `/* Mon commentaire */` |

---

## Ressources

- **Dépôt GitHub:** https://github.com/goukenn/balafon-module-igk-bviewParser.git
- **Documentation Balafon:** https://balafon.igkdev.com
- **Framework Balafon:** https://github.com/goukenn/igkdev-balafon

---

**Document généré:** 29 Décembre 2025  
**Version:** 1.0  
*Documentation complète de la syntaxe des fichiers .bview dans Balafon*