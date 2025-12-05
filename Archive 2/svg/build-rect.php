<?php
use igk\svg\SvgDocument;
use IGK\System\Console\Logger;
$svg = new SvgDocument;
list($width, $height) = $params;
$svg['viewBox'] = sprintf('0 0 %s %s', $width, $height);
$svg['width'] = $width;
$svg['height'] = $height;
$svg['fill'] = '#355';
$g = $svg->add('g');
$g->add('rect')->setAttributes([
    "x"=>"0",
    "y"=>"0",
    "width"=>$width,
    "height"=>$height
]);
echo  $s = $svg->render();
echo PHP_EOL;
echo PHP_EOL;
Logger::info('64encodeing');
echo base64_encode($s);
echo PHP_EOL;
$gd = IGKGD::Create($width, $height);
$bgColor = IGKGD::CreateColorRGB(127,255,150);
$gd->clear($bgColor); 
echo ($gd->renderText()); 
igk_exit();