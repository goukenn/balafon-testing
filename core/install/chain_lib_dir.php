<?php
use IGK\System\Console\Logger;
$core = IGK_LIB_FILE;
$app_folder = '/tmp/dummy_sites';
if (!is_link($lnk = $app_folder . "/Lib/igk") && !file_exists($lnk)) {
    igk_io_createdir(dirname($lnk));
    $dirname = dirname($core);
    Logger::info("dirname: ". $dirname);
    Logger::info("link: ". $lnk);
    $relative = igk_io_get_relativepath($lnk.'/', $dirname);
    Logger::info("relative: ".$relative);
    @symlink($relative, $lnk);
}
exit;