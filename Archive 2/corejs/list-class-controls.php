<?php
// @author: C.A.D. BONDJE DOUE
// @filename: list-class-controls.php
// @date: 20250709 19:04:56
// @desc: corejs : list class control 
// @command: balafon --run .test/corejs/list-class-controls.php
use IGK\System\Text\RegexMatcherContainer;
$regex = new RegexMatcherContainer;
$src = file_get_contents(__DIR__.'/core.js');
$c = $regex->begin('\\binitClassControl\\b', '(?<=\))', 'init-class-control')->last();
$regex->autoStore = false;
$string = $regex->appendStringDetection()->last();
    $lc = $regex->appendMultilineComment()->last();
    $mc = $regex->appendSingleLineComment()->last();
    $extra_params = $regex->begin(',', '(?=\))', 'extra-params')->last();
$regex->autoStore = true;
$c->patterns = [
    $string,
    $mc,
    $lc,
    $extra_params
];
$extra_params->patterns = [
    $string,
    $mc,
    $lc,
];
$pos = 0;
$Tcount = 0;
$loading = [];
while($g = $regex->detect($src, $pos)){
    if ($e = $regex->end($g, $src, $pos)){
        if (is_null($e->parentInfo)){
            $Tcount++;
        }
         else {
            if (($e->tokenID=='string') && ($e->parentInfo->match->tokenID=='init-class-control')){
                $loading[] = igk_str_remove_quote($e->value);
            }
         }
    }
}
sort($loading);
igk_wln_e($Tcount, $loading);