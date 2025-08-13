<?php
// @command: balafon --run .test/css/check_css.php
use igk\bcssParser\System\IO\BcssParser;
use IGK\System\Html\Css\CssMinifier;
use IGK\System\Html\Css\CssParser;
use IGK\System\Html\Dom\HtmlDocTheme;
// css check
function css_check()
{
    $css = '/* sample */body     { background-color  :indigo  }';
    $css = '/* sample */body     { apect-ration:16/9 }';
    $minifier = new CssMinifier;
    echo $minifier->minify($css);
}
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
function check($src){ 
    $g = BcssParser::ParseFromContent($src);
    $th = new HtmlDocTheme(null, 'test');
    $def = &$th->getdef();
    $def[] = ($r = $g->render()); 
    echo $r, PHP_EOL;
    echo '----------------', PHP_EOL;
    echo $th->get_css_def();
}
function minify(string $str){
    $th = new HtmlDocTheme(null, 'test');
    $def = &$th->getdef();
    $def[] = BcssParser::ParseFromContent( $str )->render();
    return $th->get_css_def();
}
// echo check('  body{.color >    d:first-child:not(.level) + .red{color:red}}');
$src = file_get_contents('/Volumes/Data/wwwroot/core/Projects/app_test/Styles/chat/main.bcss');
// echo minify('div{margin:0 20px 20px 20px;}'), PHP_EOL;
// echo minify('body:hover div:first-child{color:red;}'), PHP_EOL;
$css = new CssMinifier;
echo $css->minify(
    //$src
   //  '@media (max-width: 300px)and(min-width:250px){div{background-color: indigo !important;}}'
    // 'body  .color[   basic   ~= info    ] > d:first-child:not(.level) + .red{color:red;}'
    'body  .color[   basic   ~= info    ] > d:first-child:not(.level) + .red{color:red;}'
// echo minify(
 //   'div{margin:  0 20px 20px 20px;}' 
  //'@sm{ body{margin: 0}}' 
//  implode('', [
// <<<EOF
// body:hover div:first-child { color: red;}
// EOF
// ]
//  )
   // @xlg-screen{
    //     div.msg{
    //         margin:20px;
    //         background-color:indigo;
    //     }
    // }

//'body     { background-color:        white; color:   indigo   }'), 
),PHP_EOL;

//echo minify('body     { background-color:        white; color:   indigo   }'), PHP_EOL;
// " because of line feed: " .minify(implode("\n", [
// '/* <!-- Attributes --> */',
// 'body{color:red;}',
// '/* <!-- end:Attributes --> */'
// ]));




exit;
