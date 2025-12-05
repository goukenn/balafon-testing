<?php
// @author: C.A.D. BONDJE DOUE
// @filename: dependence.php
// @date: 20250630 16:38:34
// @desc:  vite project that depend on package 
// @command: balafon --run .test/tools/node/find-depend-on.php
// NOTE: 
// to update package .... 
// > balafon --run .test/tools/node/find-depend-on.php /Volumes/Data/Dev/Vite vue --update-with:/Volumes/Data/Dev/Vite/learn/uri_with_axios/node_modules/vue
use IGK\Helper\IO;
use IGK\System\Console\Logger;
$dir = igk_getv($params, 0) ?? igk_die('missing [in] param');
$pack = igk_getv($params, 1, 'vite');
$update = igk_getv($command->options, '--update-with');
$cpversion = $fc_update_check =  null;
if ($update){
    if (basename($update) == $pack){
        $fc_update_check = function($f, $target, $package, $pack)use(& $cpversion){
            if ($target == $cpversion->dir){
                Logger::warn('same dir');
                return;
            }
            Logger::info('update');
            IO::RmDir($target); 
            Logger::info(sprintf('copy %s => %s',$cpversion->dir, $target));
            IO::CopyFiles($cpversion->dir, $target, true);
            $package->dependencies->{$pack} = '^'.$cpversion->version;
            igk_io_w2file($f, json_encode($package, JSON_PRETTY_PRINT| JSON_UNESCAPED_SLASHES));
            // copy require module 
            foreach($cpversion->dependencies as $p){
                $v_hdir = dirname($cpversion->dir)."/".$p;
                if (is_dir($v_hdir)){
                    IO::CopyFiles($v_hdir, dirname($target)."/".$p, true);
                }
            }
        }; 
        $sm = $update.'/package.json';
        $package = json_decode(file_get_contents($sm));
        $cpversion =(object)['version'=>$package->version, 'dir'=>$update,'dependencies'=>
        isset($package->dependencies)?array_keys((array)$package->dependencies): []];
    }
}
// /Volumes/Data/Dev/Vite/learn/uri_with_axios/node_modules/vue
$T = 0;
$treated = [];
IO::GetFiles($dir, function($f)use(& $T, $pack, $fc_update_check,$cpversion, & $treated ){
    if ( preg_match("/\/package\.json$/", $f)){
        $c = realpath($f);
        if (isset($treated[$c])){
            return;
        }
        $treated[$c] = $f;
        $b = dirname($f);
        $package = json_decode(file_get_contents($f));
        if ($r = igk_conf_get($package,'dependencies/'.$pack)){ 
            Logger::print(dirname($f) . ': depend on '.$pack.":".$r);
            $T++;
            if ($fc_update_check ){
                Logger::info('try update : '.$r);
                if (strpos($r, '^')===0){
                    $r = ltrim($r, '^');
                }
                if (empty($r) || ('*'==$r) || ($cpversion->version > $r)){
                    $target = $b.'/node_modules/'.$pack;
                    $fc_update_check($f, $target, $package, $pack);                   
                }
            }
        }
    }
}, true);
Logger::success('done: '.$T);
igk_exit();