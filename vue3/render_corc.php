<?php
use igk\js\Vue3\Components\VueNoTagNode;
use IGK\System\Html\HtmlNodeBuilder;
$content = "Presentation";
$node = new VueNoTagNode;
HtmlNodeBuilder::Init($node, [
    'div.layout-px-spacing.dash_1 > div.row.row.layout-top-spacing' => [
       'div'=>$content
    ]
]);
$node->renderAJX();
exit;