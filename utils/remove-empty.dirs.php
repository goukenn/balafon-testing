<?php
// @author: C.A.D. BONDJE DOUE
// @filename: Untitled-1
// @date: 20250602 07:53:22
// @desc: remove empty directory 
// @command: balafon --run .test/utils/remove-empty-dirs.php
use IGK\Helper\IO;
use IGK\System\Console\Logger;
defined('IGK_FRAMEWORK') || igk_die('missing balafon framework');
($dir = igk_getv($params, 0) ) ?? igk_die('missing directory ');
$dirs = [];
IO::GetDirs($dir, function($q)use(& $dirs){ 
     if (IO::IsDirEmpty($q)){
        $dirs[] = $q;
        Logger::info('rm: '.$q);
        IO::RmDir($q);
        while(true){
            $pt = dirname($q);
            if (($pt==$q) && ($pt=='/')){
                break;
            }
            if ('darwin'==strtolower(PHP_OS)){
                @unlink($pt.'/.DS_Store');
            }
            if (IO::IsDirEmpty($pt)){
                IO::RmDir($pt);
                $q = $pt;
                $dirs[] = $pt;
                continue;
            }
            break;
        }
        return false;
    }
    return true;
}, true);
foreach(IO::GetFiles($dir, "/\.DS_Store$/", true) as $f){
    @unlink($f);
}
// while(count($dirs)>0 ){
//     $q = array_shift($dirs);
//     if (IO::IsDirEmpty($q)){
//         $dirs[] = dirname($q);
//         Logger::info('rm: '.$q);
//         IO::RmDir($q);
//     }
// } 
igk_wln_e($dirs);