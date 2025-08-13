<?php
// @command: balafon --run .test/css/minify.demo.php
use igk\bcssParser\System\IO\BcssParser;
use IGK\System\Html\Css\CssMinifier;
// $g = BcssParser::ParseFromContent("div{inset: 0     0 0; color:red;}");
// $src = rtrim($g->render());
// igk_wln("sample: ", $src);
// $src .= 'body{padding:0}div .igk-winui-design-editor{background-color:red; inset: 0 0   0 0;  height:400px;}';// 'div.igk-winui-design-editor div{inset:0:background-color:indigo;position:absolute;}';
$minify = new CssMinifier;
$code = <<<CODE
div{a{background:red} a.sample{background-color:blue;}
CODE;
$code = <<<CODE
div{background-color:red;.layer.over-layer{background-color:transparent;} .layer{ background-color:indigo; }  .layer {color:white; }}
CODE;
// $code = <<<CODE
// div{a:#040816;}
// CODE;


$c = BcssParser::ParseFromContent($code);
$src = $c->render();
$g = $minify->minify($src);

echo $g , PHP_EOL;

exit;