<?php
// @command: balafon --run .test/regex/regex-comprehension.php

$src = implode("\n", [
    "// one ",
    "// two ",
    "// three",
    "ondulation"
]);
// this detect the last expression until the end of the source
$h = preg_match('/\/\/.+$/', $src, $tab);
// this detect the first occurence until the end of the line 
$h = preg_match('/\/\/.+/', $src, $tab);


igk_wln_e($h, $tab);

 
