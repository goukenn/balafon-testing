# Balafon Books - Annexe E : Système d'Événements (EventHostTrait)

**Auteur** : C.A.D. BONDJE DOUE  
**Date** : 06 février 2026  
**Version** : 1.0

---

## Table des Matières

- [Introduction](#introduction)
- [Architecture du Système d'Événements](#architecture-du-système-dévénements)
- [Composants Clés](#composants-clés)
  - [AppEvent](#appevent)
  - [EventArgs](#eventargs)
  - [EventHostTrait](#eventhosttrait)
- [Implémentation Pratique](#implémentation-pratique)
  - [Création d'une Classe avec Événements](#création-dune-classe-avec-événements)
  - [Déclaration des Événements](#déclaration-des-événements)
  - [Enregistrement des Listeners](#enregistrement-des-listeners)
  - [Déclenchement des Événements](#déclenchement-des-événements)
- [Signature des Listeners](#signature-des-listeners)
  - [Objet Événement Spécial](#objet-événement-spécial)
  - [Accès aux Arguments](#accès-aux-arguments)
- [Gestion des Listeners](#gestion-des-listeners)
  - [Ajout de Listeners](#ajout-de-listeners)
  - [Suppression de Listeners](#suppression-de-listeners)
  - [Suppression Globale](#suppression-globale)
- [Accès Magique aux Événements](#accès-magique-aux-événements)
- [Patterns et Cas d'Usage](#patterns-et-cas-dusage)
  - [Pattern Observer](#pattern-observer)
  - [Pattern Publish-Subscribe](#pattern-publish-subscribe)
  - [Événements Multiplés](#événements-multiplés)
- [Différence avec les Hooks](#différence-avec-les-hooks)
- [Exemples Pratiques](#exemples-pratiques)
  - [Événements de Modèle](#événements-de-modèle)
  - [Événements d'Interface Utilisateur](#événements-dinterface-utilisateur)
  - [Événements de Workflow](#événements-de-workflow)
- [Bonnes Pratiques](#bonnes-pratiques)
- [Dépannage](#dépannage)
- [Conclusion](#conclusion)

---

## Introduction

Le **système d'événements** de Balafon est un mécanisme orienté objet qui permet à vos classes de notifier d'autres objets lorsque des changements d'état se produisent. Basé sur le pattern **Observer**, ce système utilise le trait `EventHostTrait` pour ajouter des capacités d'événements à n'importe quelle classe.

### Qu'est-ce qu'un Événement ?

Un **événement** est une notification envoyée par un objet (l'émetteur) pour signaler qu'une action ou un changement d'état s'est produit. D'autres objets (les listeners/écouteurs) peuvent s'abonner à cet événement pour réagir en conséquence.

### Pourquoi Utiliser les Événements ?

- **Découplage** : L'émetteur ne connaît pas les écouteurs
- **Extensibilité** : Ajout facile de nouveaux comportements
- **Maintenabilité** : Code modulaire et organisé
- **Réactivité** : Réaction automatique aux changements d'état
- **Orienté Objet** : Pattern orienté objet plutôt que fonctionnel

---

## Architecture du Système d'Événements

### Schéma de Fonctionnement

```
+-----------------------------------------------+
|   Classe avec EventHostTrait                  |
|                                               |
|   class A extends IGKObject {                 |
|       use EventHostTrait;                     |
|                                               |
|       private $m_action;                      |
|                                               |
|       protected function getEventObject()     |
|       {                                       |
|           return [                            |
|               'actionName' => $m_action       |
|           ];                                  |
|       }                                       |
|   }                                           |
+-------------------+---------------------------+
                    |
                    v
+-----------------------------------------------+
|   Enregistrement de Listeners                 |
|                                               |
|   $obj->addEvent('actionName', $callback)     |
+-------------------+---------------------------+
                    |
                    v
+-----------------------------------------------+
|   Changement d'État                           |
|                                               |
|   $obj->setActionName('indigo');              |
|       -> onActionNameChanged()                |
+-------------------+---------------------------+
                    |
                    v
+-----------------------------------------------+
|   Déclenchement de l'Événement                |
|                                               |
|   $m_action->invoke($this, $args)             |
+-------------------+---------------------------+
                    |
                    v
+-----------------------------------------------+
|   Exécution des Listeners                     |
|                                               |
|   $callback1($eventObject);                   |
|   $callback2($eventObject);                   |
|   $callback3($eventObject);                   |
+-----------------------------------------------+
```

---

## Composants Clés

### AppEvent

La classe `AppEvent` représente un événement qui peut être déclenché.

```php
<?php
namespace IGK\System\Core;

/**
 * Représente un événement d'application
 */
class AppEvent
{
    /**
     * Liste des listeners enregistrés
     */
    private $listeners = [];
    
    /**
     * Ajouter un listener
     * @param callable $callback Fonction à appeler
     * @return void
     */
    public function add(callable $callback)
    {
        $this->listeners[] = $callback;
    }
    
    /**
     * Retirer un listener
     * @param callable $callback Fonction à retirer
     * @return bool True si retiré, false sinon
     */
    public function remove(callable $callback): bool
    {
        $key = array_search($callback, $this->listeners, true);
        
        if ($key !== false) {
            unset($this->listeners[$key]);
            return true;
        }
        
        return false;
    }
    
    /**
     * Retirer tous les listeners
     * @return void
     */
    public function clear()
    {
        $this->listeners = [];
    }
    
    /**
     * Déclencher l'événement
     * @param object $sender Objet émetteur
     * @param EventArgs $args Arguments de l'événement
     * @return void
     */
    public function invoke($sender, EventArgs $args)
    {
        // Créer l'objet événement spécial
        $eventObject = (object)[
            'sender' => $sender,
            'args' => ['event' => $args]
        ];
        
        foreach ($this->listeners as $listener) {
            $listener($eventObject);
        }
    }
}
?>
```

### EventArgs

La classe `EventArgs` encapsule les arguments passés aux listeners.

```php
<?php
namespace IGK\System\Core;

/**
 * Arguments d'événement
 */
class EventArgs
{
    /**
     * Données de l'événement
     */
    private $data = [];
    
    /**
     * Instance vide (singleton)
     */
    private static $empty;
    
    /**
     * Obtenir une instance vide
     * @return EventArgs
     */
    public static function Empty(): EventArgs
    {
        if (self::$empty === null) {
            self::$empty = new self();
        }
        
        return self::$empty;
    }
    
    /**
     * Constructeur
     * @param array $data Données optionnelles
     */
    public function __construct(array $data = [])
    {
        $this->data = $data;
    }
    
    /**
     * Obtenir une valeur
     * @param string $key Clé
     * @param mixed $default Valeur par défaut
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        return $this->data[$key] ?? $default;
    }
    
    /**
     * Définir une valeur
     * @param string $key Clé
     * @param mixed $value Valeur
     * @return void
     */
    public function set(string $key, $value)
    {
        $this->data[$key] = $value;
    }
}
?>
```

### EventHostTrait

Le trait `EventHostTrait` ajoute les capacités d'événements à une classe.

```php
<?php
namespace IGK\System\Core\Traits;

/**
 * Trait pour ajouter des événements à une classe
 */
trait EventHostTrait
{
    /**
     * Obtenir la définition des événements
     * Retourne un tableau associatif : ['nomÉvénement' => $appEventInstance]
     * 
     * @return array
     */
    abstract protected function getEventObject(): array;
    
    /**
     * Ajouter un listener à un événement
     * 
     * @param string $eventName Nom de l'événement
     * @param callable $callback Fonction à appeler
     * @return void
     * @throws Exception Si l'événement n'existe pas
     */
    public function addEvent(string $eventName, callable $callback)
    {
        $events = $this->getEventObject();
        
        if (!isset($events[$eventName])) {
            throw new \Exception("L'événement '{$eventName}' n'existe pas");
        }
        
        $events[$eventName]->add($callback);
    }
    
    /**
     * Retirer un listener d'un événement
     * 
     * @param string $eventName Nom de l'événement
     * @param callable|null $callback Fonction à retirer, ou null pour tout retirer
     * @return void
     * @throws Exception Si l'événement n'existe pas
     */
    public function removeEvent(string $eventName, ?callable $callback = null)
    {
        $events = $this->getEventObject();
        
        if (!isset($events[$eventName])) {
            throw new \Exception("L'événement '{$eventName}' n'existe pas");
        }
        
        if ($callback === null) {
            // Retirer tous les listeners
            $events[$eventName]->clear();
        } else {
            // Retirer un listener spécifique
            $events[$eventName]->remove($callback);
        }
    }
    
    /**
     * Accès magique pour accéder aux événements comme des propriétés
     * Permet : $obj->eventName = 'value' pour déclencher l'événement
     */
    public function __set($name, $value)
    {
        $events = $this->getEventObject();
        
        if (isset($events[$name])) {
            // Déclencher l'événement
            $method = 'set' . ucfirst($name);
            
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        } else {
            // Comportement par défaut
            $this->$name = $value;
        }
    }
}
?>
```

---

## Implémentation Pratique

### Création d'une Classe avec Événements

#### Étape 1 : Structure de Base

```php
<?php
use IGK\System\Core\AppEvent;
use IGK\System\Core\EventArgs;
use IGK\System\Core\Traits\EventHostTrait;

class A extends IGKObject
{
    /**
     * Événement déclenché lors du changement de nom d'action
     */
    private $m_action;
    
    /**
     * Nom de l'action actuelle
     */
    private $m_actionName;
    
    /**
     * Utiliser le trait pour les événements
     */
    use EventHostTrait;
    
    /**
     * Constructeur
     */
    public function __construct()
    {
        // Initialiser l'événement
        $this->m_action = new AppEvent();
    }
}
?>
```

### Déclaration des Événements

#### Méthode getEventObject()

Cette méthode **obligatoire** définit les événements disponibles :

```php
<?php
class A extends IGKObject
{
    use EventHostTrait;
    
    private $m_action;
    private $m_valueChanged;
    private $m_statusUpdated;
    
    /**
     * Définir les événements disponibles
     * 
     * @return array Tableau associatif [nom => AppEvent]
     */
    protected function getEventObject(): array
    {
        return [
            'actionName' => $this->m_action,
            'valueChanged' => $this->m_valueChanged,
            'statusUpdated' => $this->m_statusUpdated
        ];
    }
}
?>
```

**Points importants** :
- La clé du tableau est le **nom de l'événement**
- La valeur est l'**instance d'AppEvent**
- Cette méthode est **abstraite** dans le trait (doit être implémentée)

### Enregistrement des Listeners

#### Syntaxe de Base

```php
<?php
$obj = new A();

// Enregistrer un listener avec une fonction anonyme
$obj->addEvent('actionName', function($e) {
    echo "Action name changed!\n";
});

// Enregistrer un listener avec une fonction nommée
function onActionNameChanged($e)
{
    echo "Action triggered\n";
}

$obj->addEvent('actionName', 'onActionNameChanged');

// Enregistrer un listener avec une méthode de classe
class Listener
{
    public function onActionChanged($e)
    {
        echo "Listener class notified\n";
    }
}

$listener = new Listener();
$obj->addEvent('actionName', [$listener, 'onActionChanged']);
?>
```

### Déclenchement des Événements

#### Pattern Standard

```php
<?php
class A extends IGKObject
{
    use EventHostTrait;
    
    private $m_action;
    private $m_actionName;
    
    /**
     * Setter avec notification
     */
    public function setActionName(string $name)
    {
        // Vérifier si la valeur change
        if ($name != $this->m_actionName) {
            // Mettre à jour la valeur
            $this->m_actionName = $name;
            
            // Notifier les écouteurs
            $this->onActionNameChanged();
        }
    }
    
    /**
     * Méthode protégée pour déclencher l'événement
     */
    protected function onActionNameChanged()
    {
        if ($this->m_action) {
            // Déclencher l'événement
            $this->m_action->invoke($this, EventArgs::Empty());
        }
    }
    
    protected function getEventObject(): array
    {
        return ['actionName' => $this->m_action];
    }
}
?>
```

**Convention de nommage** :
- Méthode publique : `setPropertyName()` pour modifier la valeur
- Méthode protégée : `onPropertyNameChanged()` pour déclencher l'événement

#### Avec Arguments Personnalisés

```php
<?php
class A extends IGKObject
{
    use EventHostTrait;
    
    private $m_action;
    private $m_actionName;
    
    public function setActionName(string $name)
    {
        if ($name != $this->m_actionName) {
            $oldValue = $this->m_actionName;
            $this->m_actionName = $name;
            
            // Déclencher avec arguments personnalisés
            $this->onActionNameChanged($oldValue, $name);
        }
    }
    
    protected function onActionNameChanged($oldValue, $newValue)
    {
        if ($this->m_action) {
            // Créer des arguments personnalisés
            $args = new EventArgs([
                'oldValue' => $oldValue,
                'newValue' => $newValue,
                'timestamp' => time()
            ]);
            
            $this->m_action->invoke($this, $args);
        }
    }
    
    protected function getEventObject(): array
    {
        return ['actionName' => $this->m_action];
    }
}

// Utilisation
$obj = new A();

$obj->addEvent('actionName', function($e) {
    // Récupérer les EventArgs depuis l'objet spécial
    $args = $e->args['event'];
    
    echo "Changed from: " . $args->get('oldValue') . "\n";
    echo "Changed to: " . $args->get('newValue') . "\n";
    echo "At: " . date('Y-m-d H:i:s', $args->get('timestamp')) . "\n";
});

$obj->setActionName('indigo');
?>
```

---

## Signature des Listeners

### Objet Événement Spécial

**IMPORTANT** : Les listeners dans Balafon reçoivent un **objet événement spécial** qui contient deux propriétés :

```php
<?php
$eventObject = (object)[
    'sender' => $sender,      // L'objet qui a déclenché l'événement
    'args' => [
        'event' => $eventArgs // Les EventArgs dans la clé 'event'
    ]
];
?>
```

### Accès aux Arguments

#### Signature Correcte du Listener

```php
<?php
// Tous les listeners doivent suivre cette signature
function myListener($e)
{
    // $e : Objet événement spécial
    // $e->sender : Objet émetteur
    // $e->args['event'] : EventArgs
    
    $sender = $e->sender;
    $eventArgs = $e->args['event'];
    
    // Accéder aux données de l'événement
    $value = $eventArgs->get('someKey');
}
?>
```

#### Exemples d'Utilisation

```php
<?php
$c = new A();

// Exemple 1 : Listener simple
$c->addEvent('actionName', function($e) {
    // Accéder au sender
    echo "Sender class: " . get_class($e->sender) . "\n";
    
    // Accéder aux EventArgs
    $args = $e->args['event'];
    echo "Event triggered!\n";
});

// Exemple 2 : Listener avec accès aux données
$c->addEvent('actionName', function($e) {
    $args = $e->args['event'];
    
    $oldValue = $args->get('oldValue');
    $newValue = $args->get('newValue');
    
    echo "Value changed from '$oldValue' to '$newValue'\n";
});

// Exemple 3 : Listener avec méthode de classe
class MyListener
{
    public function onActionChanged($e)
    {
        $sender = $e->sender;
        $args = $e->args['event'];
        
        echo "Listener notified by " . get_class($sender) . "\n";
        
        if ($args->get('newValue')) {
            echo "New value: " . $args->get('newValue') . "\n";
        }
    }
}

$listener = new MyListener();
$c->addEvent('actionName', [$listener, 'onActionChanged']);
?>
```

#### Accès Direct vs Déstructuration

```php
<?php
// Méthode 1 : Accès direct
$obj->addEvent('actionName', function($e) {
    $args = $e->args['event'];
    echo $args->get('value') . "\n";
});

// Méthode 2 : Stocker les références
$obj->addEvent('actionName', function($e) {
    $sender = $e->sender;
    $eventArgs = $e->args['event'];
    
    // Utiliser les références
    $value = $eventArgs->get('value');
    $senderClass = get_class($sender);
    
    echo "Value from $senderClass: $value\n";
});

// Méthode 3 : Vérification de présence
$obj->addEvent('actionName', function($e) {
    if (isset($e->args['event'])) {
        $args = $e->args['event'];
        
        if ($args->get('value') !== null) {
            echo "Value exists: " . $args->get('value') . "\n";
        }
    }
});
?>
```

---

## Gestion des Listeners

### Ajout de Listeners

#### Exemples d'Ajout

```php
<?php
$c = new A();

// Listener 1 : Fonction anonyme
$c->addEvent('actionName', function($e) {
    $args = $e->args['event'];
    echo "a event ---\n";
});

// Listener 2 : Fonction anonyme stockée
$listener = function($e) {
    $args = $e->args['event'];
    echo "b event ---\n";
};
$c->addEvent('actionName', $listener);

// Listener 3 : Méthode de classe
class Observer
{
    public function onActionChanged($e)
    {
        $args = $e->args['event'];
        echo "Observer notified\n";
    }
}

$observer = new Observer();
$c->addEvent('actionName', [$observer, 'onActionChanged']);
?>
```

#### Ordre d'Exécution

Les listeners sont exécutés dans l'**ordre d'enregistrement** :

```php
<?php
$c = new A();

$c->addEvent('actionName', function($e) {
    echo "1. Premier listener\n";
});

$c->addEvent('actionName', function($e) {
    echo "2. Deuxième listener\n";
});

$c->addEvent('actionName', function($e) {
    echo "3. Troisième listener\n";
});

$c->setActionName('test');

/*
Sortie :
1. Premier listener
2. Deuxième listener
3. Troisième listener
*/
?>
```

### Suppression de Listeners

#### Supprimer un Listener Spécifique

```php
<?php
$b = new A();

// Enregistrer un listener avec une référence
$b1 = function($e) {
    $args = $e->args['event'];
    echo "b event ---\n";
};

$b->addEvent('actionName', $b1);

// Enregistrer un autre listener
$b->addEvent('actionName', function($e) {
    $args = $e->args['event'];
    echo "cb event ---\n";
});

// Déclencher : les deux listeners s'exécutent
$b->setActionName('test');

/*
Sortie :
b event ---
cb event ---
*/

// Retirer le premier listener
$b->removeEvent('actionName', $b1);

// Déclencher : seul le deuxième s'exécute
$b->setActionName('test2');

/*
Sortie :
cb event ---
*/
?>
```

**Important** : Pour pouvoir retirer un listener, vous devez garder une **référence** à la fonction.

### Suppression Globale

#### Retirer Tous les Listeners

```php
<?php
$b = new A();

// Enregistrer plusieurs listeners
$b->addEvent('actionName', function($e) { echo "1\n"; });
$b->addEvent('actionName', function($e) { echo "2\n"; });
$b->addEvent('actionName', function($e) { echo "3\n"; });

// Déclencher : tous s'exécutent
$b->setActionName('test');

/*
Sortie :
1
2
3
*/

// Retirer TOUS les listeners
$b->removeEvent('actionName', null);

// Déclencher : aucun listener ne s'exécute
$b->setActionName('test2');

// Aucune sortie
?>
```

---

## Accès Magique aux Événements

Le trait `EventHostTrait` fournit un **accès magique** via `__set()` qui permet de déclencher des événements en assignant directement une valeur.

### Syntaxe

```php
<?php
$b = new A();

$b->addEvent('actionName', function($e) {
    $args = $e->args['event'];
    echo "Event triggered!\n";
});

// Utiliser l'accès magique
$b->actionName = 'Katar';

// Équivalent à :
$b->setActionName('Katar');
?>
```

### Comment ça Fonctionne ?

Lorsque vous écrivez `$obj->propertyName = $value`, le trait intercepte l'assignation :

1. Vérifie si `propertyName` existe dans `getEventObject()`
2. Si oui, cherche une méthode `setPropertyName()`
3. Appelle cette méthode avec la valeur
4. La méthode setter déclenche l'événement

```php
<?php
// Dans EventHostTrait
public function __set($name, $value)
{
    $events = $this->getEventObject();
    
    if (isset($events[$name])) {
        // Construire le nom du setter
        $method = 'set' . ucfirst($name);
        
        if (method_exists($this, $method)) {
            // Appeler le setter
            $this->$method($value);
        }
    }
}
?>
```

### Exemple Complet

```php
<?php
use IGK\System\Core\AppEvent;
use IGK\System\Core\EventArgs;
use IGK\System\Core\Traits\EventHostTrait;

class Person extends IGKObject
{
    use EventHostTrait;
    
    private $m_nameChanged;
    private $m_name;
    
    public function __construct()
    {
        $this->m_nameChanged = new AppEvent();
    }
    
    protected function getEventObject(): array
    {
        return ['name' => $this->m_nameChanged];
    }
    
    public function setName(string $name)
    {
        if ($name != $this->m_name) {
            $oldName = $this->m_name;
            $this->m_name = $name;
            $this->onNameChanged($oldName, $name);
        }
    }
    
    protected function onNameChanged($oldName, $newName)
    {
        if ($this->m_nameChanged) {
            $args = new EventArgs([
                'oldName' => $oldName,
                'newName' => $newName
            ]);
            
            $this->m_nameChanged->invoke($this, $args);
        }
    }
    
    public function getName()
    {
        return $this->m_name;
    }
}

// Utilisation
$person = new Person();

$person->addEvent('name', function($e) {
    $args = $e->args['event'];
    echo "Name changed from '" . $args->get('oldName') . "' ";
    echo "to '" . $args->get('newName') . "'\n";
});

// Méthode 1 : Via setter explicite
$person->setName('Alice');

// Méthode 2 : Via accès magique
$person->name = 'Bob';

/*
Sortie :
Name changed from '' to 'Alice'
Name changed from 'Alice' to 'Bob'
*/
?>
```

---

## Patterns et Cas d'Usage

### Pattern Observer

Le système d'événements de Balafon implémente le **pattern Observer**.

```php
<?php
/**
 * Sujet (Subject) : Objet observé
 */
class DataModel extends IGKObject
{
    use EventHostTrait;
    
    private $m_dataChanged;
    private $m_data;
    
    public function __construct()
    {
        $this->m_dataChanged = new AppEvent();
    }
    
    protected function getEventObject(): array
    {
        return ['dataChanged' => $this->m_dataChanged];
    }
    
    public function setData($data)
    {
        if ($data != $this->m_data) {
            $this->m_data = $data;
            $this->onDataChanged();
        }
    }
    
    protected function onDataChanged()
    {
        if ($this->m_dataChanged) {
            $this->m_dataChanged->invoke($this, EventArgs::Empty());
        }
    }
    
    public function getData()
    {
        return $this->m_data;
    }
}

/**
 * Observateurs (Observers)
 */
class ChartView
{
    public function onDataChanged($e)
    {
        $sender = $e->sender;
        echo "Chart updated with data: " . json_encode($sender->getData()) . "\n";
    }
}

class TableView
{
    public function onDataChanged($e)
    {
        $sender = $e->sender;
        echo "Table refreshed with data: " . json_encode($sender->getData()) . "\n";
    }
}

class SummaryView
{
    public function onDataChanged($e)
    {
        $sender = $e->sender;
        echo "Summary recalculated with data: " . json_encode($sender->getData()) . "\n";
    }
}

// Utilisation
$model = new DataModel();

$chart = new ChartView();
$table = new TableView();
$summary = new SummaryView();

// Enregistrer les observateurs
$model->addEvent('dataChanged', [$chart, 'onDataChanged']);
$model->addEvent('dataChanged', [$table, 'onDataChanged']);
$model->addEvent('dataChanged', [$summary, 'onDataChanged']);

// Modifier les données : tous les observateurs sont notifiés
$model->setData(['value' => 100, 'label' => 'Sales']);

/*
Sortie :
Chart updated with data: {"value":100,"label":"Sales"}
Table refreshed with data: {"value":100,"label":"Sales"}
Summary recalculated with data: {"value":100,"label":"Sales"}
*/
?>
```

### Pattern Publish-Subscribe

```php
<?php
/**
 * Event Bus pour Publish-Subscribe
 */
class EventBus extends IGKObject
{
    use EventHostTrait;
    
    private $events = [];
    
    /**
     * Enregistrer un événement
     */
    public function registerEvent(string $name)
    {
        if (!isset($this->events[$name])) {
            $this->events[$name] = new AppEvent();
        }
    }
    
    protected function getEventObject(): array
    {
        return $this->events;
    }
    
    /**
     * Publier un événement
     */
    public function publish(string $eventName, array $data = [])
    {
        if (isset($this->events[$eventName])) {
            $args = new EventArgs($data);
            $this->events[$eventName]->invoke($this, $args);
        }
    }
    
    /**
     * S'abonner à un événement
     */
    public function subscribe(string $eventName, callable $callback)
    {
        $this->registerEvent($eventName);
        $this->addEvent($eventName, $callback);
    }
}

// Utilisation
$bus = new EventBus();

// Abonnement de plusieurs composants
$bus->subscribe('user.login', function($e) {
    $args = $e->args['event'];
    echo "Analytics: User logged in - " . $args->get('userId') . "\n";
});

$bus->subscribe('user.login', function($e) {
    $args = $e->args['event'];
    echo "Email: Sending welcome email to user " . $args->get('userId') . "\n";
});

$bus->subscribe('user.login', function($e) {
    echo "Notification: Creating in-app notification for user\n";
});

// Publication d'un événement
$bus->publish('user.login', [
    'userId' => 123,
    'timestamp' => time()
]);

/*
Sortie :
Analytics: User logged in - 123
Email: Sending welcome email to user 123
Notification: Creating in-app notification for user
*/
?>
```

### Événements Multiplés

Une classe peut gérer plusieurs événements différents.

```php
<?php
class Order extends IGKObject
{
    use EventHostTrait;
    
    private $m_statusChanged;
    private $m_itemAdded;
    private $m_itemRemoved;
    private $m_totalChanged;
    
    private $status;
    private $items = [];
    private $total = 0;
    
    public function __construct()
    {
        $this->m_statusChanged = new AppEvent();
        $this->m_itemAdded = new AppEvent();
        $this->m_itemRemoved = new AppEvent();
        $this->m_totalChanged = new AppEvent();
    }
    
    protected function getEventObject(): array
    {
        return [
            'statusChanged' => $this->m_statusChanged,
            'itemAdded' => $this->m_itemAdded,
            'itemRemoved' => $this->m_itemRemoved,
            'totalChanged' => $this->m_totalChanged
        ];
    }
    
    public function setStatus($status)
    {
        if ($status != $this->status) {
            $oldStatus = $this->status;
            $this->status = $status;
            
            if ($this->m_statusChanged) {
                $args = new EventArgs([
                    'oldStatus' => $oldStatus,
                    'newStatus' => $status
                ]);
                $this->m_statusChanged->invoke($this, $args);
            }
        }
    }
    
    public function addItem($item, $price)
    {
        $this->items[] = $item;
        $oldTotal = $this->total;
        $this->total += $price;
        
        if ($this->m_itemAdded) {
            $args = new EventArgs(['item' => $item, 'price' => $price]);
            $this->m_itemAdded->invoke($this, $args);
        }
        
        $this->notifyTotalChanged($oldTotal, $this->total);
    }
    
    public function removeItem($item, $price)
    {
        $key = array_search($item, $this->items);
        if ($key !== false) {
            unset($this->items[$key]);
            $oldTotal = $this->total;
            $this->total -= $price;
            
            if ($this->m_itemRemoved) {
                $args = new EventArgs(['item' => $item, 'price' => $price]);
                $this->m_itemRemoved->invoke($this, $args);
            }
            
            $this->notifyTotalChanged($oldTotal, $this->total);
        }
    }
    
    private function notifyTotalChanged($oldTotal, $newTotal)
    {
        if ($this->m_totalChanged && $oldTotal != $newTotal) {
            $args = new EventArgs([
                'oldTotal' => $oldTotal,
                'newTotal' => $newTotal
            ]);
            $this->m_totalChanged->invoke($this, $args);
        }
    }
}

// Utilisation
$order = new Order();

// Écouter les changements de statut
$order->addEvent('statusChanged', function($e) {
    $args = $e->args['event'];
    $oldStatus = $args->get('oldStatus') ?: '[vide]';
    $newStatus = $args->get('newStatus');
    echo "Status: $oldStatus -> $newStatus\n";
});

// Écouter les ajouts d'items
$order->addEvent('itemAdded', function($e) {
    $args = $e->args['event'];
    echo "Item added: " . $args->get('item') . " (EUR " . $args->get('price') . ")\n";
});

// Écouter les suppressions d'items
$order->addEvent('itemRemoved', function($e) {
    $args = $e->args['event'];
    echo "Item removed: " . $args->get('item') . " (EUR " . $args->get('price') . ")\n";
});

// Écouter les changements de total
$order->addEvent('totalChanged', function($e) {
    $args = $e->args['event'];
    echo "Total: EUR " . $args->get('oldTotal') . " -> EUR " . $args->get('newTotal') . "\n";
});

// Actions
$order->addItem('Product A', 50);
$order->addItem('Product B', 30);
$order->setStatus('confirmed');
$order->removeItem('Product A', 50);

/*
Sortie :
Item added: Product A (EUR 50)
Total: EUR 0 -> EUR 50
Item added: Product B (EUR 30)
Total: EUR 50 -> EUR 80
Status: [vide] -> confirmed
Item removed: Product A (EUR 50)
Total: EUR 80 -> EUR 30
*/
?>
```

---

## Différence avec les Hooks

Le système d'événements et le système de hooks (voir Annexe B) sont deux approches différentes pour étendre le comportement.

### Comparaison

| Aspect | Événements (EventHostTrait) | Hooks (IGKEvents) |
|--------|-------------------------------|-------------------|
| **Paradigme** | Orienté Objet | Fonctionnel |
| **Portée** | Instance spécifique | Globale |
| **Déclaration** | Dans la classe (getEventObject) | Enregistrement externe |
| **Découplage** | Faible (lien objet-listener) | Fort (aucun lien) |
| **Flexibilité** | Limitée à l'instance | Flexible (n'importe où) |
| **Performance** | Légèrement plus rapide | Léger overhead |
| **Cas d'usage** | Relations objet-observateur | Extensions framework |

### Quand Utiliser Quoi ?

**Utilisez les Événements** pour :
- Relations entre objets (modèle-vue)
- Pattern Observer classique
- Communication interne à une classe
- Événements liés au cycle de vie d'un objet

**Utilisez les Hooks** pour :
- Extension du framework
- Points d'extension globaux
- Plugins et modules
- Comportements ajoutés de l'extérieur

### Exemple de Combinaison

```php
<?php
/**
 * Classe utilisant à la fois des événements et des hooks
 */
class User extends Model
{
    use EventHostTrait;
    
    private $m_nameChanged;
    private $name;
    
    public function __construct()
    {
        $this->m_nameChanged = new AppEvent();
    }
    
    protected function getEventObject(): array
    {
        return ['nameChanged' => $this->m_nameChanged];
    }
    
    public function setName($name)
    {
        if ($name != $this->name) {
            $oldName = $this->name;
            $this->name = $name;
            
            // 1. Événement orienté objet (instance spécifique)
            if ($this->m_nameChanged) {
                $args = new EventArgs([
                    'oldName' => $oldName,
                    'newName' => $name
                ]);
                $this->m_nameChanged->invoke($this, $args);
            }
            
            // 2. Hook global (tous les changements de nom)
            igk_hook(IGKEvents::HOOK_USER_NAME_CHANGED, [
                'user' => $this,
                'oldName' => $oldName,
                'newName' => $name
            ]);
        }
    }
}

// Utilisation des événements (portée instance)
$user1 = new User();
$user1->addEvent('nameChanged', function($e) {
    echo "User 1: Name changed\n";
});

$user2 = new User();
$user2->addEvent('nameChanged', function($e) {
    echo "User 2: Name changed\n";
});

// Utilisation des hooks (portée globale)
igk_reg_hook(IGKEvents::HOOK_USER_NAME_CHANGED, function($event) {
    echo "Global: Any user name changed\n";
});

$user1->setName('Alice'); // Déclenche : événement user1 + hook global
$user2->setName('Bob');   // Déclenche : événement user2 + hook global

/*
Sortie :
User 1: Name changed
Global: Any user name changed
User 2: Name changed
Global: Any user name changed
*/
?>
```

---

## Exemples Pratiques

### Événements de Modèle

#### Modèle avec Événements de Validation

```php
<?php
use IGK\System\Core\AppEvent;
use IGK\System\Core\EventArgs;
use IGK\System\Core\Traits\EventHostTrait;

class Product extends Model
{
    use EventHostTrait;
    
    private $m_beforeSave;
    private $m_afterSave;
    private $m_validationFailed;
    
    private $name;
    private $price;
    private $stock;
    
    public function __construct()
    {
        $this->m_beforeSave = new AppEvent();
        $this->m_afterSave = new AppEvent();
        $this->m_validationFailed = new AppEvent();
    }
    
    protected function getEventObject(): array
    {
        return [
            'beforeSave' => $this->m_beforeSave,
            'afterSave' => $this->m_afterSave,
            'validationFailed' => $this->m_validationFailed
        ];
    }
    
    public function save()
    {
        // Événement avant sauvegarde
        if ($this->m_beforeSave) {
            $this->m_beforeSave->invoke($this, EventArgs::Empty());
        }
        
        // Validation
        $errors = $this->validate();
        
        if (!empty($errors)) {
            // Événement d'échec de validation
            if ($this->m_validationFailed) {
                $args = new EventArgs(['errors' => $errors]);
                $this->m_validationFailed->invoke($this, $args);
            }
            
            return false;
        }
        
        // Sauvegarder en base
        $result = parent::save();
        
        // Événement après sauvegarde
        if ($this->m_afterSave) {
            $args = new EventArgs(['result' => $result]);
            $this->m_afterSave->invoke($this, $args);
        }
        
        return $result;
    }
    
    private function validate()
    {
        $errors = [];
        
        if (empty($this->name)) {
            $errors[] = 'Name is required';
        }
        
        if ($this->price <= 0) {
            $errors[] = 'Price must be positive';
        }
        
        if ($this->stock < 0) {
            $errors[] = 'Stock cannot be negative';
        }
        
        return $errors;
    }
}

// Utilisation
$product = new Product();

// Logger avant sauvegarde
$product->addEvent('beforeSave', function($e) {
    echo "Attempting to save product...\n";
});

// Envoyer notification après sauvegarde
$product->addEvent('afterSave', function($e) {
    echo "Product saved successfully!\n";
    // Envoyer notification email, mettre à jour cache, etc.
});

// Gérer les erreurs de validation
$product->addEvent('validationFailed', function($e) {
    $args = $e->args['event'];
    echo "Validation errors:\n";
    foreach ($args->get('errors') as $error) {
        echo "  - $error\n";
    }
});

// Test avec données invalides
$product->name = '';
$product->price = -10;
$product->save();

/*
Sortie :
Attempting to save product...
Validation errors:
  - Name is required
  - Price must be positive
*/
?>
```

### Événements d'Interface Utilisateur

#### Composant UI avec Événements

```php
<?php
class Button extends UIComponent
{
    use EventHostTrait;
    
    private $m_click;
    private $m_hover;
    private $m_focus;
    
    private $label;
    private $enabled = true;
    
    public function __construct($label)
    {
        $this->label = $label;
        $this->m_click = new AppEvent();
        $this->m_hover = new AppEvent();
        $this->m_focus = new AppEvent();
    }
    
    protected function getEventObject(): array
    {
        return [
            'click' => $this->m_click,
            'hover' => $this->m_hover,
            'focus' => $this->m_focus
        ];
    }
    
    public function click()
    {
        if ($this->enabled && $this->m_click) {
            $args = new EventArgs([
                'timestamp' => time(),
                'label' => $this->label
            ]);
            $this->m_click->invoke($this, $args);
        }
    }
    
    public function hover()
    {
        if ($this->m_hover) {
            $this->m_hover->invoke($this, EventArgs::Empty());
        }
    }
    
    public function focus()
    {
        if ($this->m_focus) {
            $this->m_focus->invoke($this, EventArgs::Empty());
        }
    }
    
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }
}

// Utilisation
$submitButton = new Button('Submit');

// Gérer le clic
$submitButton->addEvent('click', function($e) {
    $args = $e->args['event'];
    echo "Button clicked: " . $args->get('label') . "\n";
    echo "Processing form submission...\n";
});

// Gérer le survol
$submitButton->addEvent('hover', function($e) {
    echo "Button hovered - showing tooltip\n";
});

// Gérer le focus
$submitButton->addEvent('focus', function($e) {
    echo "Button focused - highlighting\n";
});

// Simuler interactions
$submitButton->hover();
$submitButton->focus();
$submitButton->click();

/*
Sortie :
Button hovered - showing tooltip
Button focused - highlighting
Button clicked: Submit
Processing form submission...
*/
?>
```

### Événements de Workflow

#### Machine à États avec Événements

```php
<?php
class OrderStateMachine extends IGKObject
{
    use EventHostTrait;
    
    private $m_stateChanged;
    private $m_transitionFailed;
    
    private $currentState;
    
    const STATE_PENDING = 'pending';
    const STATE_CONFIRMED = 'confirmed';
    const STATE_SHIPPED = 'shipped';
    const STATE_DELIVERED = 'delivered';
    const STATE_CANCELLED = 'cancelled';
    
    private $transitions = [
        self::STATE_PENDING => [self::STATE_CONFIRMED, self::STATE_CANCELLED],
        self::STATE_CONFIRMED => [self::STATE_SHIPPED, self::STATE_CANCELLED],
        self::STATE_SHIPPED => [self::STATE_DELIVERED],
        self::STATE_DELIVERED => [],
        self::STATE_CANCELLED => []
    ];
    
    public function __construct()
    {
        $this->currentState = self::STATE_PENDING;
        $this->m_stateChanged = new AppEvent();
        $this->m_transitionFailed = new AppEvent();
    }
    
    protected function getEventObject(): array
    {
        return [
            'stateChanged' => $this->m_stateChanged,
            'transitionFailed' => $this->m_transitionFailed
        ];
    }
    
    public function transitionTo($newState)
    {
        // Vérifier si la transition est valide
        $allowedStates = $this->transitions[$this->currentState];
        
        if (!in_array($newState, $allowedStates)) {
            // Transition invalide
            if ($this->m_transitionFailed) {
                $args = new EventArgs([
                    'from' => $this->currentState,
                    'to' => $newState,
                    'reason' => 'Invalid transition'
                ]);
                $this->m_transitionFailed->invoke($this, $args);
            }
            
            return false;
        }
        
        // Transition valide
        $oldState = $this->currentState;
        $this->currentState = $newState;
        
        if ($this->m_stateChanged) {
            $args = new EventArgs([
                'from' => $oldState,
                'to' => $newState,
                'timestamp' => time()
            ]);
            $this->m_stateChanged->invoke($this, $args);
        }
        
        return true;
    }
    
    public function getCurrentState()
    {
        return $this->currentState;
    }
}

// Utilisation
$order = new OrderStateMachine();

// Logger les changements d'état
$order->addEvent('stateChanged', function($e) {
    $args = $e->args['event'];
    echo "State: " . $args->get('from') . " -> " . $args->get('to') . "\n";
    echo "Time: " . date('Y-m-d H:i:s', $args->get('timestamp')) . "\n";
});

// Gérer les transitions invalides
$order->addEvent('transitionFailed', function($e) {
    $args = $e->args['event'];
    echo "ERROR: Cannot transition from " . $args->get('from');
    echo " to " . $args->get('to') . "\n";
    echo "Reason: " . $args->get('reason') . "\n";
});

// Workflow valide
$order->transitionTo(OrderStateMachine::STATE_CONFIRMED);
$order->transitionTo(OrderStateMachine::STATE_SHIPPED);
$order->transitionTo(OrderStateMachine::STATE_DELIVERED);

// Transition invalide
$order->transitionTo(OrderStateMachine::STATE_PENDING);

/*
Sortie :
State: pending -> confirmed
Time: 2026-02-06 12:46:28
State: confirmed -> shipped
Time: 2026-02-06 12:46:28
State: shipped -> delivered
Time: 2026-02-06 12:46:28
ERROR: Cannot transition from delivered to pending
Reason: Invalid transition
*/
?>
```

---

## Bonnes Pratiques

### 1. Nommage des Événements

```php
<?php
// ✅ BON : Noms clairs et descriptifs
protected function getEventObject(): array
{
    return [
        'nameChanged' => $this->m_nameChanged,
        'statusUpdated' => $this->m_statusUpdated,
        'itemAdded' => $this->m_itemAdded,
        'beforeSave' => $this->m_beforeSave,
        'afterDelete' => $this->m_afterDelete
    ];
}

// ❌ MAUVAIS : Noms vagues
protected function getEventObject(): array
{
    return [
        'event1' => $this->m_event1,
        'change' => $this->m_change,
        'update' => $this->m_update
    ];
}
?>
```

**Conventions** :
- Utiliser le **passé composé** pour les événements après action : `itemAdded`, `statusChanged`
- Utiliser **before/after** pour les événements de cycle de vie : `beforeSave`, `afterDelete`
- Être spécifique : `priceChanged` plutôt que `changed`

### 2. Vérification de Changement d'État

```php
<?php
// ✅ BON : Vérifier avant de déclencher
public function setStatus($status)
{
    if ($status != $this->status) {  // Vérification importante
        $this->status = $status;
        $this->onStatusChanged();
    }
}

// ❌ MAUVAIS : Déclencher systématiquement
public function setStatus($status)
{
    $this->status = $status;
    $this->onStatusChanged();  // Déclenché même si pas de changement
}
?>
```

### 3. EventArgs Informatifs

```php
<?php
// ✅ BON : Fournir toutes les infos utiles
protected function onPriceChanged($oldPrice, $newPrice)
{
    $args = new EventArgs([
        'oldPrice' => $oldPrice,
        'newPrice' => $newPrice,
        'difference' => $newPrice - $oldPrice,
        'percentChange' => (($newPrice - $oldPrice) / $oldPrice) * 100,
        'timestamp' => time()
    ]);
    
    $this->m_priceChanged->invoke($this, $args);
}

// ❌ MAUVAIS : Arguments insuffisants
protected function onPriceChanged($newPrice)
{
    $args = new EventArgs(['price' => $newPrice]);
    $this->m_priceChanged->invoke($this, $args);
}
?>
```

### 4. Vérification de l'Événement

```php
<?php
// ✅ BON : Vérifier que l'événement existe
protected function onStatusChanged()
{
    if ($this->m_statusChanged) {  // Vérification importante
        $this->m_statusChanged->invoke($this, EventArgs::Empty());
    }
}

// ❌ MAUVAIS : Pas de vérification
protected function onStatusChanged()
{
    $this->m_statusChanged->invoke($this, EventArgs::Empty());
    // Erreur si m_statusChanged est null
}
?>
```

### 5. Accès aux EventArgs dans les Listeners

```php
<?php
// ✅ BON : Accès correct à EventArgs
$obj->addEvent('valueChanged', function($e) {
    // Récupérer EventArgs depuis l'objet spécial
    $args = $e->args['event'];
    
    $oldValue = $args->get('oldValue');
    $newValue = $args->get('newValue');
    
    echo "Value changed: $oldValue -> $newValue\n";
});

// ❌ MAUVAIS : Accès incorrect
$obj->addEvent('valueChanged', function($e) {
    // Tenter d'accéder directement (erreur)
    $oldValue = $e->get('oldValue');  // Erreur : $e n'a pas de méthode get()
});
?>
```

### 6. Documentation

```php
<?php
/**
 * Classe représentant une commande
 * 
 * Événements :
 * - statusChanged : Déclenché lorsque le statut change
 *   Args: oldStatus, newStatus, timestamp
 * 
 * - itemAdded : Déclenché lors de l'ajout d'un item
 *   Args: item, price
 * 
 * - totalChanged : Déclenché lorsque le total change
 *   Args: oldTotal, newTotal
 * 
 * Signature des listeners :
 * function($e) {
 *     $sender = $e->sender;
 *     $args = $e->args['event'];
 * }
 */
class Order extends IGKObject
{
    use EventHostTrait;
    
    /**
     * Événement déclenché lors du changement de statut
     */
    private $m_statusChanged;
    
    /**
     * Événement déclenché lors de l'ajout d'un item
     */
    private $m_itemAdded;
    
    // ...
}
?>
```

### 7. Gestion des Références

```php
<?php
// ✅ BON : Conserver les références pour pouvoir retirer
class MyClass
{
    private $listeners = [];
    
    public function setupEvents()
    {
        // Stocker les références
        $this->listeners['onChange'] = function($e) {
            // Handler
        };
        
        $this->addEvent('valueChanged', $this->listeners['onChange']);
    }
    
    public function cleanup()
    {
        // Retirer proprement
        $this->removeEvent('valueChanged', $this->listeners['onChange']);
    }
}

// ❌ MAUVAIS : Impossible de retirer
class MyClass
{
    public function setupEvents()
    {
        // Pas de référence = impossible de retirer
        $this->addEvent('valueChanged', function($e) {
            // Handler
        });
    }
}
?>
```

### 8. Éviter les Boucles Infinies

```php
<?php
// ❌ DANGER : Risque de boucle infinie
class BadExample extends IGKObject
{
    use EventHostTrait;
    
    private $m_valueChanged;
    private $value;
    
    protected function getEventObject(): array
    {
        return ['valueChanged' => $this->m_valueChanged];
    }
    
    public function setValue($value)
    {
        $this->value = $value;
        $this->onValueChanged();
    }
    
    protected function onValueChanged()
    {
        if ($this->m_valueChanged) {
            $this->m_valueChanged->invoke($this, EventArgs::Empty());
        }
    }
}

$obj = new BadExample();

$obj->addEvent('valueChanged', function($e) {
    $sender = $e->sender;
    // DANGER : Appel récursif infini !
    $sender->setValue(10);
});

$obj->setValue(5); // Boucle infinie

// ✅ BON : Vérifier le changement
class GoodExample extends IGKObject
{
    use EventHostTrait;
    
    private $m_valueChanged;
    private $value;
    
    protected function getEventObject(): array
    {
        return ['valueChanged' => $this->m_valueChanged];
    }
    
    public function setValue($value)
    {
        // Vérifier le changement
        if ($value != $this->value) {
            $this->value = $value;
            $this->onValueChanged();
        }
    }
    
    protected function onValueChanged()
    {
        if ($this->m_valueChanged) {
            $this->m_valueChanged->invoke($this, EventArgs::Empty());
        }
    }
}
?>
```

---

## Dépannage

### Problème : Événement Non Déclenché

**Symptôme** :
```php
$obj->addEvent('myEvent', function($e) {
    echo "Event triggered\n";
});

$obj->triggerEvent(); // Aucune sortie
```

**Causes possibles** :
1. L'événement n'est pas initialisé dans le constructeur
2. L'événement n'est pas dans `getEventObject()`
3. La méthode `invoke()` n'est jamais appelée

**Solutions** :

```php
<?php
// Vérifier l'initialisation
public function __construct()
{
    $this->m_myEvent = new AppEvent();  // ✅ Nécessaire
}

// Vérifier getEventObject()
protected function getEventObject(): array
{
    return [
        'myEvent' => $this->m_myEvent  // ✅ Nécessaire
    ];
}

// Vérifier invoke()
protected function onMyEventTriggered()
{
    if ($this->m_myEvent) {
        $this->m_myEvent->invoke($this, EventArgs::Empty());  // ✅ Nécessaire
    }
}
?>
```

### Problème : Impossible de Retirer un Listener

**Symptôme** :
```php
$obj->addEvent('myEvent', function($e) { echo "Test\n"; });
$obj->removeEvent('myEvent', ???); // Comment retirer ?
```

**Cause** : Pas de référence au callback.

**Solution** :

```php
<?php
// Stocker la référence
$listener = function($e) {
    echo "Test\n";
};

$obj->addEvent('myEvent', $listener);

// Maintenant on peut retirer
$obj->removeEvent('myEvent', $listener);
?>
```

### Problème : Erreur "L'événement n'existe pas"

**Symptôme** :
```php
$obj->addEvent('wrongName', function($e) { });
// Exception: L'événement 'wrongName' n'existe pas
```

**Cause** : Nom d'événement incorrect.

**Solution** :

```php
<?php
// Vérifier les événements disponibles
protected function getEventObject(): array
{
    return [
        'actionName' => $this->m_action,  // Nom correct
        'statusChanged' => $this->m_statusChanged
    ];
}

// Utiliser le bon nom
$obj->addEvent('actionName', function($e) { }); // ✅ Correct
?>
```

### Problème : Événements Exécutés Plusieurs Fois

**Symptôme** :
```php
$obj->setValue(10);
// Output affiché 3 fois au lieu d'une fois
```

**Cause** : Listener enregistré plusieurs fois.

**Solution** :

```php
<?php
// Vérifier l'enregistrement
$listener = function($e) { echo "Test\n"; };

$obj->addEvent('valueChanged', $listener);
$obj->addEvent('valueChanged', $listener);  // ❌ Doublé
$obj->addEvent('valueChanged', $listener);  // ❌ Triplé

// Retirer avant de rajouter
$obj->removeEvent('valueChanged', $listener);
$obj->addEvent('valueChanged', $listener);  // ✅ Unique
?>
```

### Problème : Accès Magique Ne Fonctionne Pas

**Symptôme** :
```php
$obj->eventName = 'value';  // Aucun effet
```

**Causes possibles** :
1. Pas de méthode `setEventName()`
2. L'événement n'est pas dans `getEventObject()`

**Solution** :

```php
<?php
// Définir le setter
public function setEventName($value)  // ✅ Nécessaire
{
    if ($value != $this->eventName) {
        $this->eventName = $value;
        $this->onEventNameChanged();
    }
}

// Définir l'événement
protected function getEventObject(): array
{
    return [
        'eventName' => $this->m_eventNameChanged  // ✅ Nécessaire
    ];
}

// Maintenant l'accès magique fonctionne
$obj->eventName = 'value';  // Appelle setEventName()
?>
```

### Problème : Erreur lors de l'Accès aux EventArgs

**Symptôme** :
```php
$obj->addEvent('myEvent', function($e) {
    $value = $e->get('someKey');  // Erreur: Call to undefined method
});
```

**Cause** : Tentative d'accès direct à EventArgs sans passer par `$e->args['event']`.

**Solution** :

```php
<?php
// ❌ MAUVAIS : Accès direct
$obj->addEvent('myEvent', function($e) {
    $value = $e->get('someKey');  // Erreur
});

// ✅ BON : Accès via args['event']
$obj->addEvent('myEvent', function($e) {
    $args = $e->args['event'];  // Récupérer EventArgs
    $value = $args->get('someKey');  // Maintenant ça fonctionne
});
?>
```

---

## Conclusion

### Points Clés à Retenir

1. **Pattern Observer Orienté Objet**
   - Basé sur `AppEvent`, `EventArgs`, et `EventHostTrait`
   - Communication entre objets via notifications
   - Émetteur et écouteurs découplés

2. **Implémentation Simple**
   - Utiliser le trait `EventHostTrait`
   - Définir `getEventObject()` avec les événements
   - Initialiser les `AppEvent` dans le constructeur
   - Appeler `invoke()` pour déclencher

3. **Signature des Listeners**
   - Les listeners reçoivent un **objet événement spécial** `$e`
   - Accès au sender : `$e->sender`
   - Accès aux EventArgs : `$e->args['event']`
   - Signature : `function($e) { ... }`

4. **Gestion des Listeners**
   - `addEvent()` pour enregistrer
   - `removeEvent()` pour retirer (besoin de référence)
   - `removeEvent($name, null)` pour tout retirer

5. **Accès Magique**
   - `$obj->eventName = $value` déclenche `setEventName()`
   - Nécessite setter et définition dans `getEventObject()`

6. **Différence avec Hooks**
   - Événements : Orienté objet, portée instance
   - Hooks : Fonctionnel, portée globale
   - Combiner les deux selon les besoins

### Avantages du Système

- **Découplage** : Émetteur indépendant des écouteurs
- **Flexibilité** : Ajout/retrait dynamique de listeners
- **Maintenabilité** : Code modulaire et organisé
- **Réactivité** : Réaction automatique aux changements
- **Orienté Objet** : Pattern classique et éprouvé

### Cas d'Usage Principaux

- **Modèles** : Notification de changements d'état
- **UI Components** : Gérer interactions utilisateur
- **Workflows** : Machines à états et transitions
- **Validation** : Before/after hooks sur opérations
- **Architecture MVC** : Communication modèle-vue

### Prochaines Étapes

Pour approfondir l'utilisation des événements dans Balafon :

1. **Explorer les patterns** : Observer, Pub-Sub, State Machine
2. **Combiner avec hooks** : Utiliser les deux approches
3. **Implémenter dans vos modèles** : Ajouter notifications
4. **Créer des composants réutilisables** : UI avec événements
5. **Tester rigoureusement** : Tests unitaires des événements

### Ressources Additionnelles

- **Annexe B** : Système de hooking
- **Annexe D** : Système d'annotations
- **Documentation Balafon** : Framework principal
- **Patterns de conception** : Observer, Pub-Sub, Mediator

---

**Note** : Cette annexe fait partie de la documentation officielle Balafon Books. Pour toute question ou contribution, n'hésitez pas à consulter la communauté Balafon ou à contribuer à l'amélioration de cette documentation.

---

**Balafon Framework** - Pure PHP, SOLID Principles, Powerful Architecture