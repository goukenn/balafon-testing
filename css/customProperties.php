<?php
// @command: balafon --run .test/css/customProperties.php
use IGK\System\Html\Dom\HtmlDocTheme;
use IGK\System\Html\Dom\HtmlDoctype;

$css = new HtmlDocTheme(null, 'temp');
$css['body']= 'size:32px 12pt';
echo $css->get_css_def(true, true);
igk_exit();