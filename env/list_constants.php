<?php
// @command: balafon --run .test/env/list_constants.php
// load all constant that start with IGK_
// @desc: list all constant 
use IGK\Helper\IO;
echo "get files ....\n";
$files = IO::GetFiles(__DIR__.'/../../src/application', '/\.(php|phtml)$/', true);
echo "sort files ....\n";
sort($files);
$l = [];
$T = count($files);
$m = 1;
foreach($files as $c){
    echo "\rtreat : " .sprintf('%s/%s',$m,$T);
    $src = file_get_contents($c);
    if ($c = preg_match_all("/\\b(IGK_(?:[A-Z_]+))\\b/", $src, $tab)){
        for($i = 0; $i < $c; $i++){
            $l[$tab[1][$i]] = 1;
        }
    }
    $m++;
}
ksort($l);
echo 'done: ', PHP_EOL;
igk_io_w2file(__DIR__.'/data.json', json_encode($l, JSON_PRETTY_PRINT| JSON_UNESCAPED_SLASHES));
print_r($l);
exit;