<?php
// @command: balafon --run .test/utils/cancaseditor/gen-autoload.php cancalib_lication outfile
use IGK\System\IO\StringBuilder;

$sb = new StringBuilder;
$lib = igk_getv($params, 0) ?? igk_die('missing');
$store = igk_getv($params, 1) ?? igk_die('missing');
$dir = $lib; 
$files = igk_io_getfiles($dir, function($f){
    $lb = basename(dirname($f));
    if( preg_match("/\b(node_modules|.git)\b/", dirname($f))){
        return false;
    }
    if (basename($f)=='.autoload.js') return false;
    return preg_match("/\.js$/", $f);
}
);
$offset = strlen($dir);
foreach ($files as $value) {
    $d = '@/lib/cancalib/'.substr($value, $offset+1);
    $sb->appendLine("import '{$d}';");
}
igk_io_w2file(
    $store, 
    $sb.'');