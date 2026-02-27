<?php
// @command: balafon --run .test/array/arg_view.php
use igk\System\Console\Commands\Utility;
$arg = [
    '--info'=>'basic',
    '--a'=>'cc',null,
    '--b'=>'ok',
    'jump',
    '--flag'=>null
];
$cm =  Utility::BuildArgs($arg);
echo $cm;
exit;