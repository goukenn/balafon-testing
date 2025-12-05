<?php
// @command: balafon --run .test/treat/counting_class.php
use IGK\Helper\IO;
use IGK\System\Console\Logger;
use IGK\System\Text\RegexMatcherContainer;
$dir = '/Volumes/Data/Dev/2025/Fullter/com_igkdev_new_app/lib/app/modules';
// get dart file 
$c = IO::GetFiles($dir, '/\.dart$/', true);
$l = [];
$ren = [];
// foreach($c as $k){
//     if (preg_match('/(^WOH|main)/', $n =basename($k))) continue;
//     $hdir= dirname($k);
//     $nn = 'WOH'.implode('', array_map('ucfirst', explode('_', $n))); 
//     $l[$k] = $hdir."/".$nn;    
//     $ren[igk_io_basenamewithoutext($n)] = igk_io_basenamewithoutext($nn);
//     // igk_io_w2file($hdir.'/'.$nn, )
// }
// $src = '';
function treat_file(&$info, string $file, string $src)
{
    $N = igk_io_basenamewithoutext($file);
    // if ($N=='main')return;
    $regex = new RegexMatcherContainer;
    $regex->appendSingleLineComment();
    $regex->appendCommentDocBlock();
    $regex->appendSingleLineComment();
    $last = $regex->appendCurlyBrank()->last();
    $last->patterns = [$last];
    $regex->match("class\\s+(\\w+)", 'data');
    $pos = 0;
    $class = [];
    $ren = [];
    $match = false;
    while ($g = $regex->detect($src, $pos)) {
        if ($e = $regex->end($g, $src, $pos)) {
            if ($e->tokenID == 'data') {
                $PN = $e->captures[1][0];
                $info['classes'][] = $PN;
                if ($PN != $N) {
                    $class[] = $PN;
                } else {
                    $match = true;
                }
            }
        }
    }
    if (($tc = count($class)) > 0) {
        if ($match) {
            Logger::danger($file . " contain other mutiple class. [" . implode(", ", $class) .']');
        } else {
            if ($tc == 1) {
                if ($N == 'main'){
                    return;
                }
                // 
                Logger::info('rename '. $file);
                $src = preg_replace("/\\b".$class[0]."\\b/", $N, $src);
                igk_io_w2file($file, $src, true);
                $info['replace'][$N]=$class[0];
            } else {
                Logger::danger('mutli class not found in '. $file . ' : ['. implode(", ", $class). "]");
            }
        }
    }
}
// 
Logger::info('treat...counting file with ');
$info = [];
$info['classes'] = [];
foreach ($c as $k) {
    $src = file_get_contents($k);
    treat_file($info, $k, $src);
}
sort($info['classes'] );
igk_io_w2file(__DIR__.'/rp.json', $src = json_encode($info, JSON_PRETTY_PRINT));
igk_wln_e($src);
igk_wln_e($l); 