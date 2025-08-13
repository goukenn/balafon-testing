<?php

// check syntaxex 
// @command: balafon --run .test/module/igk.phpFormatter/check.php
use IGK\System\Text\RegexMatcherContainer;

$regex = new RegexMatcherContainer;
// if null = consider to read until of the current line:
// $regex->begin('function\\b((\\s*&\\s+)|(\\s+))([a-zA-Z_][a-zA-Z0-9_]*)', null);
// $regex->begin('function\\b((\\s*&\\s+)|(\\s+))\\b([a-zA-Z_][a-zA-Z0-9_]*)\\b', '\\bsoleil\\b', 'aux-armes');
$c = $regex->begin('function\\b((\\s*&\\s+)|(\\s+))\\b([a-zA-Z_][a-zA-Z0-9_]*)\\b', '(?<=;|})', 'function')->last();
// $curl = $regex->createPattern([
//     "begin" => "{",
//     "end" => "}",
//     "beginCaptures" => [
//         0 => [
//             'name' => 'curl-start'
//         ],
//     ],
//     'endCaptures' => [
//         0 => [
//             'name' => 'curl-end'
//         ]
//     ],
//     "tokenID" => "curl-select"
// ]);
// $curl->patterns = [
//     $curl
// ];

$_start = $regex->createPattern(['match'=>'{', 'tokenID'=>'curl-start']);
$_end = $regex->createPattern(['match'=>'}', 'tokenID'=>'curl-end']);
$_sub_curl = $regex->createPattern(['begin'=>'(?<=\{)', 'end'=>'(?=\})', 'tokenID'=>'sub-curl']);
$_sub_curl->scopedBoundary = true;
$_sub_curl->patterns = [
    $_sub_curl,
    $_start,
    $_end,
    // $regex->createPattern([
    //     'tokenID'=>'stop_sub_reading',
    //     'match'=>'(?<=\})'
    // ]),
];
$c->patterns = [
    // $curl
    $_start,
    $_end,
    $_sub_curl,
    $regex->createPattern([
        'tokenID'=>'stop_sub_reading',
         'match'=>'(?<=\})'
    ]),
];


$src = implode("\n", [
    // 'function sample de jour comme de nuit',
    // 'avec la bordure',
    // 'de soleil',
    // 'les crapeau du jour',
    // 'function de()  ;',
    // 'sans soleil. pour dire',
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
