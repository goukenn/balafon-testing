<?php
// @author: C.A.D. BONDJE DOUE
// @filename: css_to_bcss.php
// @date: 20260307 15:07:09
// @desc: 
// @command: balafon --run .test/css/css_to_bcss.php
use IGK\System\Html\Css\CssRulesParser;
use IGK\System\Html\Dom\HtmlDocTheme;

$n = new HtmlDocTheme(null, 'temp', HtmlDocTheme::TEMP_TYPE);
$source = <<<'Css'
div.ok{
color:red;
}
div.ok quote{
color: gray;
background-color:red;
}
@media (width < 100){
div.ok{
color:yello;
}
}
Css;
$p = CssRulesParser::Parse($source);
igk_wln_e($p);