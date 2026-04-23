<?php
// @author: C.A.D. BONDJE DOUE
// @filename: list-connection.php
// @date: 20260407 19:45:56
// @desc: list application present in path
// @command: balafon --run .test/language/list-connection.php
use IGK\System\Console\App;
use IGK\System\Console\Logger;
use IGK\System\Shell\OsShell;

$t = [
    'php'=>'php --version',
    'python'=>'python --version',
    'dotnet'=>'dotnet --version',
    'dotnet-script'=>'dotnet-script --version',
    'node'=>'node --version',
    'yarn'=>'yarn --version',
    'vite'=>'vite --version',
    'phpunit'=>'phpunit --version',
    'javac'=>'javac --version'
];
$missing = [];
foreach($t as $k=>$v){
    if (empty($v) && is_numeric($k))
        continue;
    if ($c =OsShell::Where($k)){
        igk_wln(App::Gets(App::BLUE, $c));
        igk_wln(' :----> ', OsShell::Exec($v));
    } else {
        $missing[] = $k;
    }
}
if ($missing){
    Logger::danger('missing app');
    igk_wln(implode(",", $missing));
}
igk_exit();