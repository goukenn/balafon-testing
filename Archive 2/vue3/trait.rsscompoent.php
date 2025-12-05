<?php
use igk\js\Vue3\Compiler\VueSFCCompiler;
use igk\js\Vue3\Components\VueComponent;
use igk\js\Vue3\Components\VueNoTagNode;
use IGK\System\Html\HtmlNodeBuilder;
igk_require_module(igk\js\Vue3::class);
// $file = '/Volumes/Data/Dev/PHP/balafon_site_dev/src/application/Projects/CarRental/Data/cardashboard/src/rsscomponents/Home.phtml';
$file = '/Volumes/Data/Dev/PHP/balafon_site_dev/src/application/Projects/CarRental/Data/cardashboard/src/rsscomponents/AppCopyRight.phtml';
$n = new VueNoTagNode; 
$builder = new HtmlNodeBuilder($n);
include($file);
$n->renderAJX();
echo PHP_EOL;
$render = VueSFCCompiler::ConvertToVueRenderMethod($n, $options);
echo "render : ".$render;
igk_exit();