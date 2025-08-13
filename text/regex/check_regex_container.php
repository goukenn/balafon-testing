<?php
use IGK\Helper\JSon;
use IGK\Helper\JSonEncodeOption;
use IGK\System\Console\Logger;
use IGK\System\Text\RegexMatcherContainer;
define('LINE_RPT', 30);
Logger::print(str_repeat('-', LINE_RPT));
Logger::print('check regex container.');
Logger::print(str_repeat('-', LINE_RPT));
$c = 'url(#line1),#040816,rgb("234,)44,45"), babba()';
$container = new RegexMatcherContainer;
// $container->begin('\b(url)\b\s*\(', '\)', 'url-target');
$lt = $container->begin('\b(rgb(a)?|hsl)\b\s*\(', '\)', 'url-target')->last();
$lt->patterns = [
$lt->begin("(\"|')", "\\1", 'string')
];
// $container->match('(?i)#([a-f0-9]{3}|[a-f0-9]{4}|[a-f0-9]{6}|[a-f0-9]{8})', 'hex-color');
$container->match(',','separator');
$container->loadRepository([
    [
        "match"=>"action"
    ],
    [
        "begin"=>"level"
    ]
    ]);
echo JSon::Encode($container->export('demo'), JSonEncodeOption::IgnoreEmpty(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
// exit;
$offset = 0;
$values = [];
$s = 0;
while ($g = $container->detect($c, $offset)){
    if ($g = $container->end($g, $c, $offset)){
        echo " RR R:".$g->tokenID."\t:".$g->value.PHP_EOL;
        if ($g->tokenID=='separator'){
            $s = 0;
            continue;
        }
        if (!$s){
            $values[] = $g->value;
            $s = 1;
        }
    }
}
print_r($values);
// Logger::print(printf($values));