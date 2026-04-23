<?php
// @command: balafon --run .test/fixed/search-php-only-script.php
use IGK\Helper\IO;
use IGK\System\Console\Logger;

$c = igk_getv($params, 0) ?? IGK_LIB_DIR;
$files = IO::GetFiles($c, '/\.(php|phtml|pinc|pcss)$/', true);
foreach($files as $f){
    if (!file_exists($f)){
    Logger::warn('missing file:'.$f);
    continue;
    }
    if (trim(file_get_contents($f))=='<?php'){
        Logger::info('file: '.$f);
    }else{
    }
}
Logger::success('done');
igk_exit();