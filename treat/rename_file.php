<?php
// @command: balafon --run .test/treat/rename_file.php dir of dart file 
use IGK\Helper\IO;
use IGK\System\Console\Logger;

$dir = igk_getv($params, 0);
$c = IO::GetFiles($dir, '/\.dart$/', true);
$l = [];
$ren = [];
foreach($c as $k){
    if (preg_match('/(^WOH|main)/', $n =basename($k))) continue;
    $hdir= dirname($k);
    $nn = 'WOH'.implode('', array_map('ucfirst', explode('_', $n))); 
    $l[$k] = $hdir."/".$nn;    
    $ren[igk_io_basenamewithoutext($n)] = igk_io_basenamewithoutext($nn);
}
$src = '';
Logger::info('treat...');
foreach($c as $k){
    $src = file_get_contents($k);
    foreach($ren as $ss=>$vv){
        $src = preg_replace("/\\b".$ss."\\b/", $vv, $src);        
    }
    igk_io_w2file($k, $src);
}
Logger::info("rename... ");
foreach($l as $k=>$v){
    rename($k, $v);
}
igk_wln_e($l);