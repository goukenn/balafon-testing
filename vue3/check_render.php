<?php
use IGK\Helper\ViewHelper;
use igk\js\Vue3\Compiler\VueSFCCompiler;
use igk\js\Vue3\Components\VueNoTagNode;
use IGK\System\Html\HtmlNodeBuilder;
$file = $params[0];
$t = new VueNoTagNode;
$builder = new HtmlNodeBuilder($t);
ob_start();
ViewHelper::Inc($file, compact('ctrl', 't', 'builder'));
$f = ob_get_contents();
if (!empty($f)){
    $t->load($f);
}
ob_end_clean();
$src =VueSFCCompiler::ConvertToVueRenderMethod($t);
echo "render ---ajx : ";
// $t->renderAJX();
echo $src;
exit;