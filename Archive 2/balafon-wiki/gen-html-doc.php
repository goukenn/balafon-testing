<?php

// @author: C.A.D. BONDJE DOUE
// @filename: gen-html-doc.php
// @date: 20251016 13:47:31
// @desc: 
// @command: balafon --run .test/balafon-wiki/gen-html-doc.php
// | 


use function igk_html_host as _h;
$n = _h('div.main', _h('p', 'first page'));
$n->renderAJX();

// sortie:
// <div class="main"><p>first page</p></div>

igk_exit();


$n = igk_create_node('div');

$n->setClass([
    'sam'=>true,
    'basic'=>true,
    't-d'=>true,
    'bas'=>false,
    'sample'=>true
]);

// output:
//      <div class="sam basic t-d sample"></div>

$n->renderAJX();
igk_exit();