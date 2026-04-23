<?php
// @command: balafon --run .test/wiki/check-markdown.php mdfile*

$f = igk_getv($param, 0); 
$f = __DIR__.'/data/one.md';
$t = igk_create_node();
$src = file_get_contents(
    $f
    );
$md = $t->div()->markdown($src, 
['allowLinkDocument'=>true]);
$md->renderAJX();
igk_exit();