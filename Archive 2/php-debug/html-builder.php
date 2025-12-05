<?php
use igk\js\Vue3\Compiler\VueSFCCompiler;
use igk\js\Vue3\Compiler\VueSFCRenderNodeVisitorOptions;
use igk\js\Vue3\Components\VueComponent;
use igk\js\Vue3\Components\VueNoTagNode;
use IGK\System\Html\HtmlNodeBuilder;
igk_require_module(igk\js\Vue3::class);
// function test_run_builder($b){
//     $t = new VueNoTagNode;
//     $builder = new HtmlNodeBuilder($t);
//     $builder($b); 
//     $src = VueSFCCompiler::ConvertToVueRenderMethod($t);
//     return $src;
// }
// $g = test_run_builder([
//     'div' => 'Home',
//     ['@_t:div' => 'Info']
// ]);
// echo $g.PHP_EOL;
$o = new VueSFCRenderNodeVisitorOptions;
$o->test = true;
$d = igk_create_notagnode();
$vp = new VueComponent;
$vp->info()->sample();
$builder = new HtmlNodeBuilder($d);
$app_main = $builder->setup("vue_component(div.mycomponent)", [
    "nav" =>  [
        ["@_t:li" => ["vue_router_link" => ['@' => ['/'], "gohome"]]],
        ["@_t:li" => ["vue_router_link" => ['@' => ['/about'], "gotoabout"]]],
    ],
    "main"=>[
        "vRouterView"=>[]
    ]
]); 
echo $app_main->render();
igk_exit();