<?php 
// @author: C.A.D. BONDJE DOUE
// @filename: compose.create-project.php
// @date: 20260115 14:28:55
// @desc: install balafon locally - with clone of balafon core project https://github.com/goukenn/igkdev-balafon.git
// @command: balafon --run .test/core/installer/compose.create-project.php
use IGK\Helper\IO;
use IGK\System\Console\Logger;

$cli = IGK_LIB_DIR.'/bin/balafon';
$BS =  IGK_DEV_DIR.'/balafon-install';
$dir = igk_getv($params, 0) ?? $BS;
if ($dir != $BS){
    $clib = realpath(IGK_LIB_DIR.'/../../../');
    if (is_dir($dir)){
        IO::RmDir($dir, true);
    }
    IO::CreateDir($dir);
    shell_exec("ln -s {$clib}/* $dir/");
    @unlink($dir.'/src');
    IO::CreateDir($dir.'/src/Lib');
    @symlink(IGK_LIB_DIR, $dir.'/src/Lib/igk');
    Logger::success('output: '.$dir);
}
chdir($dir);
IO::RmDir('vendor');
@unlink('tmp.txt');
echo shell_exec("cd {$dir} && ./composer.phar create-project 1>&2");
Logger::success('install complete.');
igk_exit();