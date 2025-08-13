<?php
// @command: balafon --run .test/io/moveto_httd.php
use IGK\Helper\IO;
use IGK\System\Console\Logger;
use IGK\System\IO\Path;
$src = '/Volumes/Data/Media/Pictures/2025/all_pictures';
$dest = '/Volumes/HHD128Go/all_pictures';
while(true){
$count = 0;
$moved = 0;
foreach(IO::GetFiles($src,"/\.(jp(e)?g|mov|mp4|heic|png|gif|cr2)$/i", true) as $file){
    $n = basename($file);
    if (file_exists($dest_d = Path::Combine($dest, $n))){
        unlink($file);
        $count++;
    }else{
        $size = filesize($file);
        Logger::print('move: '.$file . ' - ('.$size.')');
        if ($size < 500 * 409600){
            rename($file, $dest_d);
            $moved ++;
        } else{
            Logger::info('skipped');
        }
    }
}
Logger::success('done');
Logger::print('unlink : '. $count);
Logger::print('moved : '. $moved);
if (($count==0) && ($moved==0)){
    break;
}
sleep(30);
}
Logger::success('finish');
igk_exit();