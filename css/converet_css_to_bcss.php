<?php
// @author: C.A.D. BONDJE DOUE
// @filename: converet_css_to_bcss.php
// @date: 20260308 13:43:59
// @desc: convert css to bcss definition
// @command: balafon --run .test/css/converet_css_to_bcss.php file*
use IGK\System\IO\Path; 

$file = igk_getv($params, 0) ?? igk_die('missing required file');
if (!is_file($file)) {
    (!is_file($cf = Path::Combine(__DIR__, $file))) && igk_die('missing file');
    $file = $cf;
}  
echo igk_css_convert_to_bcss(file_get_contents($file));
igk_exit();