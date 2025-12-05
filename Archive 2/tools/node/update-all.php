<?php
// @author: C.A.D. BONDJE DOUE
// @filename: find-max-top-vite.php
// @date: 20250630 16:38:34
// @desc: vite maximum node modules
use IGK\Helper\IO;
use IGK\System\Console\Logger;
IO::CreateDir($g = __DIR__.'/sample');
$r = IO::CopyFiles($g, '/tmp');
igk_wln_e($r);
$dir = igk_getv($params, 0) ?? igk_die('missing params');
$pack_dir = igk_getv($params, 1); // igk_die('missing params');
$pack = basename($pack_dir);
$cversion = (object)[];
$T = 0;
$bversion = json_decode(file_get_contents($pack_dir.'/package.json'));
$cversion->version = $bversion->version;
IO::GetFiles($dir, function($f)use( $pack_dir, $pack,& $T, $cversion){
    if ( preg_match("/node_modules\/".$pack."\/package\.json$/", $f)){
        $b = dirname($f);
        if ($pack_dir == $b){
            Logger::warn("same - dir");
        }else{
            $package = json_decode(file_get_contents($f));
            if ( $package->version<$cversion->version){ 
            Logger::info("replace: ".$b. " ". $package->version." => ".$cversion->version);
            IO::RmDir($b);
            IO::CopyFiles($pack_dir, $b, true);
            $T++;
        }
        }
        // $package = json_decode(file_get_contents($f));
        // if (!isset($cversion->version) || ($cversion->version <=$package->version)){
        //     $cversion->version = $package->version;
        //     $cversion->file = $f;
        // }
    }
}, true);
Logger::success('done: '.$T);
igk_exit();