<?php
use IGK\System\IO\StringBuilder;
$sb = new StringBuilder;
$dir = '/Volumes/Data/Dev/Vite/cancaseditor/src/lib/cancalib';
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
igk_io_w2file('/Volumes/Data/Dev/Vite/cancaseditor/src/lib/cancalib/.autoload.js', $sb.'');