<?php
// @command: balafon --run .test/woh/detect_entries_uri.php [dir]
use IGK\Helper\IO;
use IGK\System\Console\Logger;

($dir = igk_getv($params, 0)) ?? igk_die('missing directory');
$files = IO::GetFiles($dir, '/\.dart$/', true);
$tab = [];
$excludes = explode('|', 'WOHApiEndpoints.dart|main.dart');
foreach($files as $c ){
    if (in_array(basename($c), $excludes)) 
        continue;
    $src = file_get_contents($c);
    if ($tc = preg_match_all('/\.apiEndPoint(?:\})?([^\\s\\b\?\$\)\'"]+)/', $src, $matches)){
        $i = 0;
        while($tc>0){
            $tc--;
            $mc = $matches[1][$i];
            $tab[$mc] = 1;
            $i++;
        }
    }
}
ksort($tab);
igk_wln(json_encode($tab, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
Logger::success('done');