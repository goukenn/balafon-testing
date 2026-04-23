<?php
// @author: C.A.D. BONDJE DOUE
// @filename: detect-end-line.php
// @date: 20260407 20:03:04
// @desc: 
// @command: balafon --run .test/regex/detect-end-line.php
use IGK\System\Text\RegexMatcherContainer;

$src = implode("\n",[
    "// information",
    "// sample",
    "// partage dsk et de jois",
    " "
]);
$regex = new RegexMatcherContainer;
$regex->match("\/\/((.*)(sk)?)?", "comment");
$pos=0;
while($g = $regex->detect($src, $pos)){
    if ($e = $regex->end($g, $src, $pos)){
        igk_wln("----",json_encode($e->value));
    }
}
igk_wln_e("done");