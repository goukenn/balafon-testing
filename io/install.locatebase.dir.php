<?php
// @command: balafon --run .test/io/install.locatebase.dir.php

require_once getenv('IGK_SITE_DEV_DIR').'/src/application/Lib/igk/Inc/core/installer.helper.pinc';
echo InstallerHelper::LocateBaseDir("d:/home/data/vison", 4), PHP_EOL;
exit;