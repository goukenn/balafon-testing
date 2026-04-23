<?php
// @author: C.A.D. BONDJE DOUE
// @filename: treat_files.php
// @date: 20250903 13:15:18
// @desc: treat all svg ios sf symbols
use igk\ios\SfSymbols\Helper;

igk_require_module('igk\\ios\\SfSymbols');
$file = $params[0];
$content = file_get_contents($file);
$p = Helper::TreatSvg($content);
echo $p;
igk_exit();