<?php
// @command: balafon --run .test/balafon-wiki/claude/glue-document.php
$f = '/tmp/list.txt';
$c = explode("\n", file_get_contents($f));
$cfile = [];
$count = '0';
while(count($c)){
    $q = array_shift($c);
    if ($q){
        igk_io_w2file(__DIR__.'/file_'.$count.'.doacx', file_get_contents($q));
        $count++;
    }
}
igk_wln_e("the counting file : ", $count);