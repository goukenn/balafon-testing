<?php
// @command: balafon --run .test/text/regex/export_regext_container.php

use IGK\Helper\JSon;
use IGK\Helper\JSonEncodeOption;
use IGK\System\IO\File\TmLanguage\Converters\RegexMatcherContainerTmLanguageConverter;
use IGK\System\Text\RegexMatcherContainer;

$regex = new RegexMatcherContainer;

$array = $regex->begin("\[","\]","array")->last();

$regex->loadRepository([
    'sample'=>[
        'match'=>'sample'
    ]
    ]);
$array->patterns = [
    $regex->createPattern(['match'=>"one:","tokenID"=>"letter", "name"=>"sample"]),
    & $array
];


$converter = new RegexMatcherContainerTmLanguageConverter;

echo JSon::Encode($converter->convert($regex), JSonEncodeOption::IgnoreEmpty(), JSON_PRETTY_PRINT);

exit;
