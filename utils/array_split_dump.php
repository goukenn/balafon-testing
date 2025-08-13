<?php
// @command: balafon --run .test/utils/array_split_dump.php
$file = igk_getv($params, 0) ?? igk_die('missing file');
$src = file_get_contents($file);
echo '$src = implode("\n", ['.PHP_EOL;
echo implode(','."\n", array_map(function($a){
    $a = str_replace("'", "\\'", $a);
    return sprintf("'%s'", $a);
}, explode("\n", $src))). PHP_EOL;
echo "]);";
echo PHP_EOL;
igk_exit();