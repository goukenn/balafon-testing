<?php
// @command: balafon --run .test/bcss/check_css_minifier.php
use IGK\System\Html\Css\CssMinifier;

$src = 'background-color:red;';
$minifier = new CssMinifier();
igk_start_time(__FILE__);
$g = $minifier->minify($src);
echo json_encode(['time'=>igk_execute_time(__FILE__)]);