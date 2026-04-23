<?php
// @command: balafon --run .test/regex/regex-comprehension.php

$src = implode("\n", [
    "// one ",
    "// two ",
    "// three",
    "ondulation"
]);
$h = preg_match('/\/\/.+$/', $src, $tab);
$h = preg_match('/\/\/.+/', $src, $tab);
igk_wln_e($h, $tab);