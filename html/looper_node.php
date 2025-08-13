<?php
use IGK\Controllers\SysDbController;
$n = igk_create_node();
// $n->article(SysDbController::ctrl(), __DIR__ . '/looper_article.html', [
//          ['firstName' => 'Charles', 'name'=>'BONDJE'],
//          ['firstName' => 'Romeo'], 
// ]);
// $n->loop(3)->li()->loop('$raw')->span()->Content = 'item ... {{$raw}}';
$n->loop([
    ['first'=>'One'],
    ['first'=>'Second'],
    ['Pascal'=>'Last', 'first'=>'doSome'],
])->li()->loop('$raw')->span()->Content = 'item ... {{ $key }} : {{$raw}}';
$n->renderAJX();
igk_exit();
?>
<li *for="$raw"><?php (function ($_context_raw) {
                    foreach ($_context_raw as $raw) : ?>
            loop on
    <?php endforeach;
                })($raw) ?>