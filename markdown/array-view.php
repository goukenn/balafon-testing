<?php
// @command: balafon --run .test/markdown/array-view.php
$d = <<<'Markdown'
| Paramètre | Type | Description |
|-----------|------|-------------|
| `$parameters` | `ReflectionParameter[]` | Tableau des paramètres de réflexion |
| `$providedArgs` | `array` | Arguments fournis (indexés ou associatifs) |
Markdown;
$n = igk_create_node('div');
$n->markdown($d);
$n->renderAJX();
igk_exit();