<?php
// @command: balafon --run .test/html/namespace.php
use IGK\System\Html\Dom\HtmlNode;
use function igk_html_host as _h;
function igk_html_node_details()
{
    $n = new HtmlNode('details');
    $n->summary()->content = 'summary for sample ';
    return $n;
}
function svg_details()
{
    $n = new HtmlNode('details');
    $n->title()->Content = 'svg details';
    $n->summary()->content = 'summary for sample ';
    return $n;
}
// $l = igk_reg_component_package();
// print_r(array_keys($l));
$rec = _h('svg:rect')->setAttributes([
    'id' => 'rc-background',
    'x' => '10',
    'y' => '10',
    'width' => '100',
    'height' => '100',
    'fill' => 'red',
    'rx' => '10px',
    'stroke' => 'accent'
]);
$r = _h(
    'svg:svg',
    _h('svg:text.block', 'C.A.D. BONDJE DEVELOPER')
    ->setAttributes([
        'x'=>10,
        'y'=>20
    ]),
    _h(
        'svg:defs',
        _h(
            'svg:clipPath',
            clone($rec)
        )->setAttributes([ 
            'id'=>'myClip'
        ])
    ),
    _h('svg:use')->setAttributes([
        'clip-path' => 'url(#clipPath)'
    ]),
    $rec 
);
$t = igk_create_node('div');
$t->add($r);
$t->add(_h('bmc:button'));
$t->add(_h('svg:ellipse'));
$r->add(_h('svg:ellipse')->setAttributes([
    'fill' => 'orangered',
    'clip-path'=>'url(#myClip)'
]));
$t->add($r);
$t->style()->Content = <<<CSS

.block{
    font-size: 18pt;
    font-family: Roboto;
    fill: green;
}

CSS;
$t->renderAJX();
igk_exit();