<?php
// @command: balafon --run .test/io/unlink_empty_pics.php
use IGK\Helper\IO;
use IGK\System\Console\Logger;
($dir = igk_getv($params, 0))?? igk_die("required dir");
$files = IO::GetFiles($dir, "/\.jpeg$/", true);
foreach($files as $f){
    if (($g = filesize($f))<= 4096){
        unlink($f);
        Logger::info('rm: '.$f);
    }
}
Logger::success('done');
igk_exit();