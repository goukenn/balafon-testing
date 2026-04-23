<?php
use igk\js\Vue3\Compiler\VueSFCCompiler;
use igk\js\Vue3\Components\VueComponent;
use IGK\System\Html\HtmlNodeBuilder;

igk_require_module(igk\js\Vue3::class);
$n = igk_create_node('notagnode');
$builder = new HtmlNodeBuilder($n);
use function igk_resources_gets as __;
$builder(
    [
         'notagnode'=> ['span'=>'item1','p'=>'item2' ]
    ]
    );
$e = VueSFCCompiler::ConvertToVueRenderMethod($n);
igk_wln($e);
$n->renderAJX();
    igk_exit();