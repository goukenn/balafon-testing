<?php


$g = "/Volumes/Data/Dev/PHP/balafon_site_dev/src/application/Lib/igk/Ext/winui/Components/HorizontalPane/Scripts/igk.winui.horizontalScrollPane.js";

$s = igk_io_collapse_path($g);
echo $s . PHP_EOL;

$asset = "/Volumes/Data/Dev/PHP/balafon_site_dev/src/public/assets/_lib_/Ext/winui/Components/HorizontalPane/Scripts/igk.winui.horizontalScrollPane.js";
$s = igk_io_collapse_path($asset);
echo $s . PHP_EOL;
if (is_file($asset))
    @unlink($asset);
echo "create link ? " .igk_io_symlink($g , $asset);

$m = igk_default::ctrl()->getDeclaredDir()."/Scripts/default.js";


// $sys_uri  = igk_html_get_system_uri($m, null, true);


// $g = IGKResourceUriResolver::getInstance()->resolve($m);
$sr = IGK_LIB_DIR."/Scripts/igk.js";
$exist = file_exists($sr);
igk_debug(1);
$g = IGKResourceUriResolver::getInstance()->resolve($sr); 

igk_wln_e("resolving = ", $g);

exit;



