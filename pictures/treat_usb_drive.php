<?php
// @command: balafon --run .test/pictures/treat_usb_drive.php
// @desc: remove all jpeg | mov from usb drive folder 
use IGK\Helper\IO;
use IGK\System\Console\Logger;

$dir = igk_getv($params, 0) ?? igk_die('missing drive or directory');
$sdir = igk_getv($params, 1) ?? 'jpeg|mov';
$g = IO::GetFiles($dir, "/\.(".$sdir.")/", false);
foreach($g as $k){
    if (preg_match("/\(|\)/", $k)){
        unlink($k);
        Logger::print('remove :'.$k);
    }
}
Logger::success('done');
exit;