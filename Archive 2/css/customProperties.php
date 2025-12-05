<?php
// @command: balafon --run .test/css/customProperties.php
use IGK\System\Html\Dom\HtmlDocTheme;
use IGK\System\Html\Dom\HtmlDoctype;
$css = new HtmlDocTheme(null, 'temp');
// add : size property to css definition litteral 
// add : location property to css definition litteral 
// add : x property to css definition litteral 
// add : y property to css definition litteral 
$css['body']= 'size:32px 12pt';
echo $css->get_css_def(true, true);
exit;