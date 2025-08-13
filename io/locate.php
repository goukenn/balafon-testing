<?php
// @command: balafon --run .test/io/locate.php /Volumes/Data/Dev
use IGK\Helper\IO;
use IGK\System\Console\Logger;
$dir = igk_getv($params, 0);
$pattern = igk_getv($params, 1);
$match = function($p, &$g, $type) use($pattern){
    if (preg_match("/\.vscode|\.git|\\bnode_modules\\b/i", $p)){
        Logger::info('skip => '.$p);
        $dir = $type=='dir'?$p : dirname($p);
        $g[$dir] = 1;
        return false;
    }
    return preg_match('/'.$pattern.'/', $p);
};
foreach(IO::GetFiles($dir, $match, true) as $k){
    Logger::warn($k);
}
Logger::success('done');
igk_exit();