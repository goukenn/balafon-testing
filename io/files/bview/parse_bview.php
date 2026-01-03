<?php
// @command: balafon --run .test/io/files/bview/parse_bview.php file

use igk\bviewParser\System\IO\BviewParser;
use igk\bviewParser\System\IO\IBviewParserOptions;
use IGK\Helper\Activator;
use IGK\System\DataArgs;
use IGK\System\Html\HtmlNodeBuilder;
$file = igk_getv($params, 0) ?? igk_die('missing file');
$content = file_get_contents($file);
$ctrl = ForemJobDashboardController::ctrl(true);
$inf = Activator::CreateNewInstance(IBviewParserOptions::class, 
[
    'ctrl'=>$ctrl,
    'raw'=>[
        'x'=>100
    ]
]
)
;
// passing $context to view 
$content = <<<'BView'
igk_block([[:@raw->x]]){
- sample {{ $raw->x }} aaa
}

BView;



function igk_html_node_igk_block(int $i){
    $n =igk_create_node('div');
    $n->content = 'Sample data ';
    $n->span()->content = 'the new : '.$i;
    return $n;
}

$c = BviewParser::ParseFromContent($content, null);

$n = igk_create_notagnode();
$builder = new HtmlNodeBuilder($n);                 
$builder($c->data, null, $inf->to_array());

igk_wln_e($c->data, $n);