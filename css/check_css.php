<?php
// @command: balafon --run .test/css/check_css.php
use igk\bcssParser\System\IO\BcssParser;
use IGK\System\Html\Css\CssMinifier;
use IGK\System\Html\Css\CssParser;
use IGK\System\Html\Dom\HtmlDocTheme;
/**
* auto generate doc.
* @return mixed
*/
function css_check()
{
    $css = '/* sample */body     { apect-ration:16/9 }';
    $minifier = new CssMinifier;
    echo $minifier->minify($css);
}
/**
* auto generate doc.
* @return mixed
*/
function calc_check()
{
    $src = 'body{width: calc(2em + 3px); }';
    $g = BcssParser::ParseFromContent($src);
    $th = new HtmlDocTheme(null, 'test');
    $def = &$th->getdef();
    $def[] = ($r = $g->render()); 
    echo $r, PHP_EOL;
    echo '-', PHP_EOL;
    echo $th->get_css_def();
}
/**
* auto generate doc.
* @param mixed $src
* @return mixed
*/
function check($src){ 
    $g = BcssParser::ParseFromContent($src);
    $th = new HtmlDocTheme(null, 'test');
    $def = &$th->getdef();
    $def[] = ($r = $g->render()); 
    echo $r, PHP_EOL;
    echo '----------------', PHP_EOL;
    echo $th->get_css_def();
}
/**
* auto generate doc.
* @param string $str
* @return mixed
*/
function minify(string $str){
    $th = new HtmlDocTheme(null, 'test');
    $def = &$th->getdef();
    $def[] = BcssParser::ParseFromContent( $str )->render();
    return $th->get_css_def();
}
$src = file_get_contents('/Volumes/Data/wwwroot/core/Projects/app_test/Styles/chat/main.bcss');
$css = new CssMinifier;
echo $css->minify(
    'body  .color[   basic   ~= info    ] > d:first-child:not(.level) + .red{color:red;}'
   // @xlg-screen{
),PHP_EOL;
igk_exit();