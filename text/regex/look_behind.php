<?php 
// @command: balafon --run .test/regex/look_behind.php

$l = [];
$g =  [
    "bonjour",
    "tout",
    "monde"
];
$m = implode("\n",$g);
$c = preg_match("/^\s*(\w+)/", $m, $l, 0, 8);
print_r($l);
$c = preg_match("/^\s*(\w+)/",  substr($m,8), $l, 0, 8);
print_r($l);
igk_wln_e("done");
$c = preg_match("/(?<=(?:(?:'|\"|\})))\s*(\|)\s*/", "'primar} |", $l, 0, 4);
print_r($l);
igk_wln_e(compact('l','c'));