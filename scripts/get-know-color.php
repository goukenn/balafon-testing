<?php
// @author: C.A.D. BONDJE DOUE
// @filename: get-know-color.php
// @date: 20250811 08:36:01
// @desc: get balafon system web nkow colors
// @command: balafon --run .test/scripts/get-know-color.php
use IGK\System\Console\Logger;
require_once IGK_LIB_DIR .'/Styles/igk_css_colors.phtml';
$g = IGKGlobalColor::getInstance()->getGlobals(); // GetGlobalColor();
Logger::info("global colors");
$tab = array_keys($g);
sort($tab);
igk_wln_e(implode('|', $tab)); //array_keys($g));