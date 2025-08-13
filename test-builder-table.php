<?php
$n = igk_create_node('div');
$n->table()->build([1,2,2, ['name'=>'Paloma....']], ['', 'name']);
$n->renderAJX();
exit;