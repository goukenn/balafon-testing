<?php
// @command: balafon --run .test/image/touch-png.php
use igk\svg\SvgDocument;
use IGK\System\Modules;
igk_require_module(Modules::igk_svg());
$file = igk_getv($params, 0)  ?? 'out.png';
$width = igk_getv($params, 1) ?? 100;
$height = igk_getv($params, 2) ?? 100;
$svg = new SvgDocument;
$svg->setSize($width, $height);
$svg->rect($width, $height)->fill("red");
$svg->rect($width, $height/2)->fill("indigo");
$svg->rect($width/3, $height/3)->fill("aqua");
$svg->savePng($file);
`open $file`;
igk_wln_e("one : ".$file, $svg->render());