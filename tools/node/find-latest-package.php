<?php
// @author: C.A.D. BONDJE DOUE
// @filename: find-max-top-vite.php
// @date: 20250630 16:38:34
// @desc: vite maximum node modules
// @command: balafon --run .test/tools/node/find-latest-package.php dir [package]
use IGK\Helper\IO;
$dir = igk_getv($params, 0) ?? igk_die('missing params');
$pack = igk_getv($params, 1, 'vite'); // igk_die('missing params');
$cversion = (object)[];
IO::GetFiles($dir, function($f)use(& $cversion, $pack){
    if ( preg_match("/node_modules\/".$pack."\/package\.json$/", $f)){
        $package = json_decode(file_get_contents($f));
        if (!isset($cversion->version) || ($cversion->version <=$package->version)){
            $cversion->version = $package->version;
            $cversion->file = $f;
        }
    }
}, true);
igk_wln_e($cversion);