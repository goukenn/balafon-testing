<?php
// @desc: build assets  
// @command: balafon --run .test/asset.php
use IGK\System\Console\BalafonCommand;
use IGK\System\IO\CoreFileSystem;
$file = $ctrl->getAssetsDir()."/css/main.css";
if (1 || !file_exists($file)){
    $g = BalafonCommand::Exec(sprintf(
        "--request:uri %s assets/Styles/balafon.css --render", $ctrl->getName()));
    igk_io_w2file($file, $g);  
}
$g = $ctrl->asset("css/main.css");
$m = "/_prj_/".basename($ctrl->getDeclaredDir())."/Data/assets";
$ts = sha1($m, false);
igk_wln_e(
    __FILE__.":".__LINE__, $file,
    $g, $m, $ts);