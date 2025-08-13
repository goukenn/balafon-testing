<?php
/// Check single views
use IGK\Helper\ViewHelper;
use igk\js\Vue3\Components\VueNoTagNode;
use IGK\System\Html\HtmlNodeBuilder;
$file = $params[0];
$t = new VueNoTagNode;
$builder = new HtmlNodeBuilder($t);
ViewHelper::Inc($file, compact('ctrl', 't', 'builder'));
echo $t->render();
exit;