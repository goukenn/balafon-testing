<?php
// @command: balafon --run .test/html/loop-node.php
use igk\bviewParser\System\IO\BviewParser;
use IGK\Controllers\SysDbController;
use IGK\System\Console\Logger;
use IGK\System\Html\Dom\HtmlNode;
use IGK\System\Html\HtmlNodeBuilder;
use IGK\System\Text\RegexMatcherContainer;


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