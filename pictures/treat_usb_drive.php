<?php
// @command: balafon --run .test/pictures/treat_usb_drive.php
use IGK\Helper\IO;
use IGK\System\Console\Logger;
$dir = '/Volumes/HHD128Go/movies';
$g = IO::GetFiles($dir, "/\.(jpeg|mov)/", false);
foreach($g as $k){
    if (preg_match("/\(|\)/", $k)){
        unlink($k);
        Logger::print('remove :'.$k);
    }
}
Logger::success('done');
exit;