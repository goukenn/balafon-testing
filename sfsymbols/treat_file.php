<?php
// balafon --run .test/sfsymbols/treat_file.php %1
use igk\ios\SfSymbols\Helper;
igk_require_module(igk\ios\SfSymbols::class);
$file = $params[0];
$content = file_get_contents($file);
$p = Helper::TreatSvg($content);
echo $p;
exit;