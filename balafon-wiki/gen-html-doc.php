<?php
// @author: C.A.D. BONDJE DOUE
// @filename: gen-html-doc.php
// @date: 20251016 13:47:31
// @desc: 
// @command: balafon --run .test/balafon-wiki/gen-html-doc.php
use function igk_html_host as _h;

$n = _h('div.main', _h('p', 'first page'));
$n->renderAJX();
igk_exit();
$n = igk_create_node('div');
$n->setClass([
    'sam'=>true,
    'basic'=>true,
    't-d'=>true,
    'bas'=>false,
    'sample'=>true
]);
$n->renderAJX();
igk_exit();