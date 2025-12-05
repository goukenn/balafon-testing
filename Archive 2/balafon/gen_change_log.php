<?php
// @command: balafon --run .test/balafon/gen_change_log.php
// + | --------------------------------------------------------------------
// + | 
// + |
use IGK\Helper\IO;
use IGK\System\IO\StringBuilder;
($dir = igk_getv($params, 0)) ?? igk_die('missing directory');
$sb = new StringBuilder;
$h = IO::GetFiles($dir, $regex = "/balafon\.(?P<version>.+)\.zip$/") ;
rsort($h);
$sb->appendLine('# Change Log');
foreach($h as $f){
    preg_match($regex, basename($f), $tab);
    $ver = $tab['version'];
    $sb->appendLine(sprintf('## [%s]', $ver));
    $sb->appendLine('- ');
}
echo $sb.'';
igk_exit();