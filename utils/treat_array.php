<?php
// @command: balafon --run .test/utils/treat_array.php definition 
use IGK\System\Console\Logger;
function treat_array(string $src){
    $tab = explode('|', $src);
    sort($tab);
    return implode('|', $tab);
};
$s = igk_getv($params, 0) ?? igk_die('missing param');
Logger::info('output:'."\n");
echo treat_array($s).PHP_EOL;
igk_exit();