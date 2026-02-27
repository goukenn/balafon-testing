<?php
// @author: C.A.D. BONDJE DOUE
// @filename: toLocalPath.php
// @date: 20260114 00:31:58
// @desc: test to local path
// @command: balafon --run .test/io/toLocalPath.php
use IGK\System\Console\Logger;
use IGK\System\IO\Path;
function toLocalPath(string $path, ?string $cwd=null):string{
    $cwd = $cwd ?? getcwd();
    $c = igk_uri($path);
    $absolute = false;
    if (PHP_OS_FAMILY=='Window'){
        $absolute = !preg_match('/^([a-zA-Z]:|\/\/)/', $c);
    } else {
        $absolute = ($c[0] == '/');
    }
    if (!$absolute){
        $c = Path::FlattenPath(Path::Combine($cwd, $c));
    } 
    return igk_dir($c);
}
Logger::info('writing: ?'.(toLocalPath('home') == getcwd().'/home') );
Logger::info('writing: ?'.(($c = toLocalPath('./home')) == getcwd().'/home') );
igk_wln($c);
Logger::info('writing: ?'.(($c = toLocalPath('../home')) == getcwd().'/home') );
igk_wln($c);
Logger::info('writing: ?'.toLocalPath('/home'));
igk_wln_e('done');