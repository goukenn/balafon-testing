<?php
chdir(dirname(__DIR__));
$cmd = '/Volumes/Data/Dev/PHP/balafon_site_dev/src/application/Lib/igk/bin/balafon';
$argv = [$cmd, '--version'];
include $cmd;