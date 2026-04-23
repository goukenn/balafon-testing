<?php
// @command: balafon --run .test/tools/io/rm_empty_dir.php
use IGK\Helper\IO;
use IGK\System\Console\Logger;

$help = property_exists($command->options,'-h');
if ($help){
    Logger::warn('Help:');
    Logger::print('Usage : dir [regex] [options]');
    igk_exit();
}
$path = igk_getv($params, 0) ?? __DIR__;
$regex = igk_getv($params, 1) ?? '/.*/';
if ($path == '.'){
    $path = __DIR__;
}else {
    $path = realpath($path);
}
$regex = igk_getv($params, 1) ?? '/.*/';
$no_recursive = property_exists($command->options,'--no-recursive');
$dirs = igk_io_getdirs($path, $regex, !$no_recursive);
if ($dirs)
while(count($dirs)>0){ 
    $dir = array_shift($dirs);
    $c = 0;
    if ($hdir = @opendir($dir)){
        while( ($h = readdir($hdir)) != feof($hdir)){
            if (($h == '.') || ($h=='..')) continue;
            $c++;
            break;
        }
        closedir($hdir);
    }
    if ($c == 0){
        Logger::info('remove : '.$dir);
        IO::RmDir($dir);
        $pdir = dirname($dir);
        if ($pdir != '/')
            array_push($dirs, $pdir);
    }
} 
Logger::print('done');
igk_exit();