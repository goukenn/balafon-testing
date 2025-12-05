<?php
// @command: balafon --run .test/io/install.locatebase.dir.php
require_once '/Volumes/Data/Dev/PHP/balafon_site_dev/src/application/Lib/igk/Inc/core/installer.helper.pinc';
echo InstallerHelper::LocateBaseDir("d:/home/data/vison", 4), PHP_EOL;
exit;