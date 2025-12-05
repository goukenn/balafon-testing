<?php
use IGK\System\Text\RegexMatcherContainer;
if ( file_exists($fs = __DIR__.'/balafon.js')){
    $src = file_get_contents($fs);
} else{
$src = igk_sys_balafon_js(null, true);
$src = str_replace('\'use strict\';', '', $src);
$src = igk_js_minify($src);
igk_io_w2file($fs, $src);
}
$regex = new RegexMatcherContainer;
$regex->match('(?i)(\'|")igk(\\.[a-z_][a-z0-9_]*)+\\1', 'match_regex');
$list = [];
$pos = 0;
while($g = $regex->detect($src, $pos)){
    if ($e = $regex->end($g, $src, $pos)){
        $key = igk_str_remove_quote($e->value);
        $list[$key] = 1;
    }
}
ksort($list);
Logger::warn('balafon namespaces');
Logger::print($list);
// no gain of size 
// $list = array_keys($list);
// sort($list);
// $g = strlen($src);
// foreach($list as $k=>$v){
//     $src = str_replace($v, '_ns['.$k.']', $src);
// }
// igk_wln($dt = str_replace('"', '\'', json_encode($list)));
// $src.= $dt;
// $dg = strlen($src);
igk_wln_e("done"); // --- ", $g - $dg);