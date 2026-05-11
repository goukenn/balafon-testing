<?php
// @author: C.A.D. BONDJE DOUE
// @filename: Untitled-1
// @date: 20250602 07:53:22
// @desc: remove empty directory  
// @command: balafon --run .test/utils/remove-empty.dirs.php /path/to/dir
use IGK\Helper\IO;
use IGK\System\Console\Logger;
/**
 * @var array $params
 * @var mixed $command
 */
defined('IGK_FRAMEWORK') || igk_die('missing balafon framework');
($dir = igk_getv($params, 0) ) ?? igk_die('missing directory ');
$dirs = [];
$excludes = igk_getv($command->options, '--exclude') ?? ['.git', '.vscode', 'node_modules'];
if (!is_array($excludes)){
    $excludes = explode(',',$excludes); 
}
/**
* auto generate doc.
* @param array $excludes
* @param string $path
* @return mixed
*/
function isExclude(array $excludes , string $path){
    $q = $path;
    return in_array($q, $excludes) || in_array(basename($q), $excludes);
}

IO::GetDirs($dir, function($q)use(& $dirs, & $excludes){ 
    if (isExclude($excludes, $q))
        return false;
    $is_darwing = 'darwin'==strtolower(PHP_OS);
    if (IO::IsDirEmpty($q)){
        $dirs[] = $q;
        Logger::info('rm: '.$q);
        IO::RmDir($q);
        while(true){
            $pt = dirname($q);
            if (($pt==$q) && ($pt=='/')){
                break;
            }
            if ($is_darwing){
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
$cdir = IO::GetFiles($dir, "/\.DS_Store$/", true) ?? [];
foreach($cdir as $f){
    @unlink($f);
}
igk_wln_e(json_encode(['removed_dirs'=>$dirs], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));