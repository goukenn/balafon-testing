<?php
// @command: balafon --run .test/vite/load_compile_vue.php file*
use igk\js\Vue3\System\IO\VueSFCFile;

$file = igk_getv($params, 0) ?? igk_die('missing file.vue param');
if (!file_exists($file)){
    igk_die('file is missing or cannot read');
}
$sf = new VueSFCFile;
$sf->loadFile( $file); 
echo $sf->compile();
igk_exit();