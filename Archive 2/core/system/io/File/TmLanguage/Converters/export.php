<?php
// @author: C.A.D. BONDJE DOUE
// @filename: export.php
// @date: 20250716 14:23:14
// @desc: export btm syntaxe 
// @command: balafon --run .test/core/system/io/File/TmLanguage/Converters/export.php

use IGK\Helper\JSon;
use IGK\System\Text\RegexMatcherContainer;
use IGK\System\IO\File\TmLanguage\Converters\RegexMatcherContainerTmLanguageConverter;


$regex = new RegexMatcherContainer;
$regex->match('baraka');
$n = $regex->match('hello','hello')->last();
$word = $regex->match('\\w+','word')->last();
$n->name = "basics";

$word->patterns = [
    $n,
    ["match"=>"baraka"]
];



$g = new RegexMatcherContainerTmLanguageConverter;
$s = $g->convert($regex);

echo JSon::Encode($s, null, JSON_PRETTY_PRINT)."\n";
igk_exit();
