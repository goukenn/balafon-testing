<?php

chdir(dirname(__DIR__));
$cmd = getenv('IGK_SITE_DEV_DIR').'/src/application/Lib/igk/bin/balafon';
$argv = [$cmd, '--version'];
include $cmd;