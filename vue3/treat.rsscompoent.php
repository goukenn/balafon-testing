<?php
// @author: C.A.D. BONDJE DOUE
// @filename: treat.rsscompoent.php
// @desc: server server component-
// @command: balafon --run .test/vue3/treat.rsscompoent.php
use igk\js\Vue3\Compiler\VueSFCCompiler;
use igk\js\Vue3\Components\VueComponent;
use igk\js\Vue3\Components\VueNoTagNode;
use IGK\System\Html\HtmlNodeBuilder;

$module = igk_get_module('igk.js.Vue3') ?? igk_die('missing module');
$ctrl ?? igk_die('missing controller');
$file = igk_getv($params, 0) ?? igk_die('missing file');
$n = new VueNoTagNode; 
$builder = new HtmlNodeBuilder($n);
include($file);
$n->renderAJX();
echo PHP_EOL;
$render = VueSFCCompiler::ConvertToVueRenderMethod($n, $options);
echo "render : ".$render;
igk_exit();