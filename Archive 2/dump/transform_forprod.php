<?php
// transform to export - to ttre.be
// balafon --run .test/dump/transform_forprod.php
use IGK\System\Console\Logger;
$file = '/Volumes/Data/Projects/TogotechRecyling/DbBackup/wp_2023_.sql';
$o = file_get_contents($file);
$uri = 'https://ttre.be';
$o = str_replace('http://localhost:7700', $uri, $o);
$o = str_replace('http://localhost', $uri, $o);
$o = str_replace('http://ttre.be', $uri, $o); 
$o = str_replace('0000-00-00 00:00:00', date('Y-m-d').' 00:00:00', $o);
// $o = str_replace('wor7051_','wp_2023_',$o);
igk_io_w2file($file, $o);
Logger::success('Done');
igk_exit();