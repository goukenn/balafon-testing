<?php
use IGK\System\Console\Colorize;
use IGK\System\Console\Logger;
use IGK\System\Text\RegexMatcherContainer;
function test_format_read_css($src){
    $regexContainer = new RegexMatcherContainer;
    $item = $regexContainer->begin("\\b(background-color|background)\\b", ";|(?=>})")->last();
    $item->tokenID = 'property.definition.css';
    $item->patterns = [
        (object)[
            'begin'=>'(:)',
            'end'=>'(?=>\}|;)',
            'tokenID'=>'selection',
            'beginCaptures'=>[
                0=>function($v){
                    return "<span>".$v."</span>";
                }
            ]
        ]
    ];
    $offset = 0;
    while ($g = $regexContainer->detect($src, $offset)){
        if ($e = $regexContainer->end($g, $src, $offset)){
            igk_wln("value : ".$e->value);
        }
    }
}
$src = 'background: red;';
test_format_read_css($src);
igk_exit();
$src = "hey!! abacab avec la data mm sample ";
preg_match("/(ab(a(c))ab).*(mm)/", $src, $cap, PREG_OFFSET_CAPTURE, 0);
// print_r($cap);
// echo json_encode($chainlist, JSON_PRETTY_PRINT);
$options = null;
$l = RegexMatcherContainer::TreatCaptures([
    // 2 => function ($v) {
    //     return " --- " . $v . " --- ";
    // },
    4 => function ($v) {
        return "@@@".strtoupper($v)."@";
    },
    1 => function ($v) {
        return "<div>" . $v . "</div>";
    }
], $cap, $src, $options);
Logger::SetColorizer(new Colorize);
Logger::print("[the value prepresentation :]".json_encode(compact('l')));
igk_exit();
$captures = [
    1 => ["name" => "prefix"],
    2 => ["name" => "suffix"]
];
$src = 'Hey! presentat bonjour tout le monde';
preg_match("/(bon)j(our)/", $src, $cap, PREG_OFFSET_CAPTURE, 0);
$v = RegexMatcherContainer::_TreatCaptures($captures, $cap, $src);
Logger::print($v);
exit;