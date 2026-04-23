<?php
// @author: C.A.D. BONDJE DOUE
// @filename: get-installed-conf-file.php
// @date: 20251128 09:23:34
// @desc: get installed configuration file
// @command: balafon --run .test/balafon/scripts/get-installed-conf-file.php
use IGK\Constants;
use IGK\System\Console\Logger;

$p = IGKEnvironment::GetGlobalConfigurationPath(getcwd());
Logger::warn("configuration.configuration-flie: ");
Logger::print($p);
igk_exit(); 