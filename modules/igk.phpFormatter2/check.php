<?php
// @command: balafon --run .test/module/igk.phpFormatter/check.php
use IGK\System\Text\RegexMatcherContainer;

$regex = new RegexMatcherContainer;
$c = $regex->begin('function\\b((\\s*&\\s+)|(\\s+))\\b([a-zA-Z_][a-zA-Z0-9_]*)\\b', '(?<=;|})', 'function')->last();
$_start = $regex->createPattern(['match'=>'{', 'tokenID'=>'curl-start']);
$_end = $regex->createPattern(['match'=>'}', 'tokenID'=>'curl-end']);
$_sub_curl = $regex->createPattern(['begin'=>'(?<=\{)', 'end'=>'(?=\})', 'tokenID'=>'sub-curl']);
$_sub_curl->scopedBoundary = true;
$_sub_curl->patterns = [
    $_sub_curl,
    $_start,
    $_end,
];
$c->patterns = [
    $_start,
    $_end,
    $_sub_curl,
    $regex->createPattern([
        'tokenID'=>'stop_sub_reading',
         'match'=>'(?<=\})'
    ]),
];
$src = implode("\n", [
    'function pratique(){ avec { joie; } pour sample lada',
    ' tous } avec determination',
    'de pascal freddy',
    'function data()',
    'sdata s ;',
    'function info(){}',
]);
$pos = 0;
while ($g = $regex->detect($src, $pos)) {
    if ($e = $regex->end($g, $src, $pos)) {
        $tid = $e->tokenID;
        if (is_null($e->parentInfo)){
            igk_wln(json_encode(['tokenID' => $tid, 'value' => $e->value, 'pos' => $pos], JSON_PRETTY_PRINT));
        }
    }
}
igk_wln_e('doe');