<?php
// @author: C.A.D. BONDJE DOUE
// @filename: composer-installer.php
// @date: 20260112 11:04:01
// @desc: check compose installer 
// @command: balafon --run .test/core/composer-installer.php

use IGK\Composer\Installer;
use IGK\Helper\IO;
define('IGK_COMPOSE_DEBUG_INSTALLER' , 1);

$dir = __DIR__.'/install-output';
if (is_dir($dir)){
    IO::RmDir($dir, true);
}
IO::CreateDir($dir);
chdir($dir);
$_SERVER['argc'] = '';
$_SERVER['argv'] = $params;
Installer::PostInstall();