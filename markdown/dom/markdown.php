<?php
// @command: balafon --run .test/markdown/dom/markdown.php
$s = <<<EOF
```bash
# Importer les données
balafon --db:import NomControleur fichier.json
```
EOF;
$n = igk_create_node('div');
$n->markdown($s);
$n->renderAJX();igk_exit();