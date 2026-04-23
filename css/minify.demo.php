<?php
// @command: balafon --run .test/css/minify.demo.php
use igk\bcssParser\System\IO\BcssParser;
use IGK\System\Console\Logger;
use IGK\System\Html\Css\CssMinifier;

$minify = new CssMinifier;
$code = <<<CODE
div{a{background:red} a.sample{background-color:blue;}
CODE;
$code = <<<CODE
div{background-color:red;.layer.over-layer{background-color:transparent;} .layer{ background-color:indigo; }  .layer {color:white; }}
CODE;
$c = BcssParser::ParseFromContent($code);
$src = $c->render();
Logger::info('src:');
Logger::print( $src);
Logger::info('output:');
$g =  $minify->minify($src);
echo $g , PHP_EOL;
igk_exit();