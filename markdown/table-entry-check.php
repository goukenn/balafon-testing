<?php
// @author: C.A.D. BONDJE DOUE
// @filename: table-entry-check.php
// @date: 20260330 13:51:29
// @desc: cancel check definition 
// @command: balafon --run .test/markdown/table-entry-check.php

$src = "\|Information | du jour| mardi";
$c = preg_match_all('/(?<!\\\)\|/', $src, $tab);
igk_wln($src, json_encode($src), json_encode($tab));
$l = preg_match('/(?:.*(?<!\\\)\|)+.*(\\n)?/', $src, $tab);
igk_wln_e($l, $src, "end:", json_encode($tab));