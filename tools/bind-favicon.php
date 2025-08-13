<?php
// @command: balafon --run .test/tools/bind-favicon.php
use IGK\System\Console\Logger;
use IGK\System\IO\Path;
($dir = igk_getv($params, 0)) ?? igk_die("required output directory");
$gd = IGKGD::Create(512,512);
$s = igk_ob_get_func(function()use($gd){
    $gd->render(null,null);
});
igk_io_w2file(Path::Combine($dir, 'favicon.ico'), $s);
Logger::success('done');
igk_exit();