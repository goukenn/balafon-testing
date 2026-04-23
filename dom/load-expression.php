<?php
// @command: balafon --run .test/dom/load-expression.php
use IGK\Controllers\SysDbController;

$n = igk_create_node('div');
$n->load(<<<'HTML'
<ul>
<li *for="2" *title="$raw->x">{{ $raw }} 
    <span *title="$raw">indication</span>
</li>
</ul>
HTML,
[
    'ctrl'=>SysDbController::ctrl(),
    'raw'=>['x'=>333]
]);
$n->renderAJX();
igk_exit();