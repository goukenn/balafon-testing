<?php
// @author: C.A.D. BONDJE DOUE
// @filename: backtick-removal.php
// @date: 20260327 08:44:50
// @desc: research in all phpscript invocation of backtick
// @command: balafon --run .test/fixed/php8.5/backtick-removal.php
// + | --------------------------------------------------------------------
// + | 
// + |
use IGK\Helper\IO;
use IGK\System\Console\Logger;
use IGK\System\IO\StringBuilder;
use IGK\System\Text\RegexMatcherContainer;
use IGK\System\Text\RegexMatcherUtility;

$dir = igk_getv($params, 0) ?? igk_die('missing arg');
is_dir($dir) || is_dir($dir = __DIR__."/".$dir) || igk_die('missing directory'.$dir);
function igk_fix_php_treatBacktickExec(string $file, & $storage=null){
    $sb = new StringBuilder;
    $src = file_get_contents($file);
    $regex = new RegexMatcherContainer;
    $regex->appendStringDetection('string', true);
    RegexMatcherUtility::AppendPhpHereDoc($regex);
    $regex->appendMultilineComment();
    $regex->appendSingleLineComment();
    $regex->begin('`', '`', 'command');
    $regex->begin('\?>', '<\?(=|php\\b)', 'php-tag');
    $pos=0;
    $lpos = 0;
    $debug = igk_is_debug() || igk_is_debug('fix_php');
    $fc_handle = [
        'command'=>function($e, $sb, $src)use(& $lpos){
            $n = trim($e->value,'`');
            $sb->append(substr($src, $lpos, $e->from-$lpos));
            $sb->append('shell_exec("'.$n.'")');
            $lpos = $e->to;
        }
    ];
    while($g = $regex->detect($src, $pos)){
        if ($e = $regex->end($g, $src, $pos)){
            $id = $e->tokenID;
            $debug && Logger::info('token-id:'.$id);
            if ($id && ($fc = igk_getv($fc_handle, $id))){
                $fc($e, $sb, $src, $pos);
            }
        }
    }
    if ($lpos>0){
        $sb->append(substr($src, $lpos));
    }
    if (!$sb->isEmpty()){
        if ($debug){
            Logger::info('update: '.$file);
            Logger::print(''.$sb);
        }
        if (!is_null($storage)){
            $storage[$file] = ''.$sb;
        } else{
            igk_io_w2file($file, ''.$sb);
        }
    }
}
$excludir = ['vendor'];
$files = [];
$storage = [];
IO::GetFiles($dir, function($f)use(& $files, & $storage){
    if (is_file($f) && preg_match('/\.php$/', $f)){
        $rp = realpath($f);
        if (!isset($files[$rp])){
            igk_fix_php_treatBacktickExec($rp, $storage);
            $files[$rp] = 1;
        }
    }
}, true, $excludir);
foreach($storage as $k=>$v){
    Logger::info('update: '.$k);
    igk_io_w2file($k, $v);
}
Logger::success('done '.count($storage));
igk_exit();