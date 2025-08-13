<?php
// @author: C.A.D. BONDJE DOUE
// @filename: document.php
// @date: 20230406 11:43:19
// @desc: testing svg document 
// + | --------------------------------------------------------------------
// + | check for comment info
// + | balafon --run .test/svg/document.php
// + |
use igk\svg\SvgDocument;
use igk\svg\System\Html\Dom\SvgfeGaussianBlur;
igk_require_module('igk\svg');
$doc = new SvgDocument;
$W = $H = 300;
$doc->setSize($W,$H);
$doc->rect($W, $H)->fill('indigo');
$doc->view()->viewBox(0,0,30,30);
$doc->circle(30,50,50)
->stroke("red")
->fill('blue');
$doc->defs()->filter()->setId('f1')->feGaussianBlur(
    SvgfeGaussianBlur::SOURCE_GRAPHICS,
    15
);
$doc->g()->rect(40, 40)->setLocation(30,30)->fill("red")->useFilter('url(#f1)');
echo $doc->render();  
exit;