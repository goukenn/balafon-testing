<?php
// @command: balafon --run .test/reflection/remove-global-funct.php
use IGK\Helper\StringUtility;
use IGK\System\Console\Logger;
use IGK\System\Php\Helper\PhpRemoveGlobaFunc;
use IGK\System\Text\RegexMatcherContainer;
use IGK\System\Text\RegexMatcherUtility;

$file = __DIR__.'/remove-global-func/data/check.php';
$file = '/Volumes/Data/Dev/PHP/balafon_site_dev/src/application/Packages/Modules/igk/redis/.module.pinc';
$file = '/Volumes/Data/Dev/PHP/balafon_site_dev/src/application/Packages/Modules/ionicons/.module.pinc';
$src = file_get_contents($file);
$f = new PhpRemoveGlobaFunc;
echo $f->remove($src), PHP_EOL;
igk_exit();