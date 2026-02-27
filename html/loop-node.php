<?php
// @command: balafon --run .test/html/loop-node.php
use igk\bviewParser\System\IO\BviewParser;
use IGK\Controllers\SysDbController;
use IGK\System\Console\Logger;
use IGK\System\Html\Dom\HtmlNode;
use IGK\System\Html\HtmlNodeBuilder;
use IGK\System\Text\RegexMatcherContainer;
/*
$c = __DIR__ . "/article-demo.phtml";
// $n = igk_create_node();
// $n->article(SysDbController::ctrl(), $c,  ['name'=>'basic']);
// igk_wln_e($n->render());
// $exp = igk_str_detect_pipe('"BASIC" | lowercase', $pipe);
// igk_str_pipe();
// igk_wln_e($exp, $pipe, igk_str_pipe_value('ABcc', "lowercase | append;'--'"));
$n = igk_create_notagnode();
// $n = igk_create_node('div'); 
$data = [
    (object)["type" => "em"],
    (object)["type" => "px", "name" => "pixel unit"]
];
$n->loop($data)->div()->Content = '{{ $raw->type }}{{ $raw->name | uppercase | format;\" - [%s]\" }}';
$s = $n->render();
igk_wln($s);
$n = igk_create_node('div');
$n->ul()->loop(range(1, 3))->host(function ($a, $i) {
    $a->li()->Content = $i;
    $a->p()->loop($i)->span()->content = 'local : ';
});
Logger::info('second: ');
$n->renderAJX();
echo str_repeat(PHP_EOL, 3);
Logger::info('third: ');
$n = igk_create_notagnode();
$n->ul()->li()->loop(['orange', 'tomatoes', 'mangoes'])
    ->Content = '{{ $raw | uppercase }}';
// host(function($a, $i){
//     $a->li()->Content = '{{ $raw | uppercase }}';
// });
$n->renderAJX();*/
echo str_repeat(PHP_EOL, 3);
Logger::info('four: ');
$parsed = BviewParser::ParseFromContent(implode("\n", [
    'div > loop([[:@raw]]){',
    ' - {{ $raw | uppercase }}',
    '}'
]));
$builder = new HtmlNodeBuilder;
$builder($parsed->data, null, [
    'mangoes',
    'potatoes',
    'tomatoes'
]);
$builder->t->renderAJX();
echo PHP_EOL;
Logger::success('done');
igk_exit();