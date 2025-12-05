<?php
// @command: balafon --run .test/balafon-wiki/gen-bview-demonstration.php

use igk\bviewParser\System\IO\BviewParser;
use IGK\System\ArrayMapKeyValue;
use IGK\System\Html\HtmlActiveAttrib;
use IGK\System\Html\HtmlNodeBuilder;
use IGK\System\IO\Configuration\ConfigurationReader;

use function igk_resources_gets as __;
use function igk_html_host as _h;
igk_require_module('igk/bviewParser');


$r = BviewParser::ParseFromContent(implode("\n", [
    'div.j > loop([[:@raw->list]]){ ',
        '- item {{ $raw }} ',
    '}'
]));
$n = _h('div');
$builder = new HtmlNodeBuilder($n);
$c = $builder($r->data, null,(object)[
    'raw'=>(object)[
        'x'=>8,
        'list'=>[1, 2, 3]
         
    ]
]);


igk_wln_e($n);

// $a = 'info:sample, litteral,x:845,disable';
// $r = new ConfigurationReader();
// $r->activeAttribute = new HtmlActiveAttrib;
// $r->separator = ':';
// $r->delimiter = ',';
// $r->escape_start = "[";
// $r->escape_end = ']';
// $v_activa_attrib = []; // HtmlNodeTagExplosionDefinition::_GetActiveAttribute($a);
// $attr = ArrayMapKeyValue::Map(function ($k, $v) {
//     if (is_null($v)) {
//         if (strpos($k, "@") === 0) {
//             return [$k = ltrim($k, '@'), new HtmlActiveAttrib];
//         }
//         return null;
//     }
//     return [$k, $v];
// }, (array)$r->read($a));

// igk_wln_e($attr);


function igk_html_node_bview_demo($title=null, $c=null, $b=null, $options=null){
    $n = _h('div.bview-demo',);
    if ($title){
        $f = '%s';
        $f .= $b ? ' -[ %s ]' : '';
        $n->h1()->content = sprintf($f, $title, $b);
    }
    return $n;
}
$n = _h('div.home-page',);

$n->bview(__DIR__.'/litteral.bview') ;


$n->renderAJX();
igk_exit();