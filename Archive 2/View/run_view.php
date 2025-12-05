<?php
use IGK\System\Html\Dom\HtmlNode;
use IGK\System\Html\HtmlNodeBuilder;
$ctrl = AppTestProject::ctrl();
$ctrl->register_autoload();
$t = new HtmlNode("div");
$builder = new HtmlNodeBuilder($t) ;
include $params[0];
$t->renderAJX();
exit;