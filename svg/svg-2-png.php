<?php
// @author: C.A.D. BONDJE DOUE
// @filename: svg-2-png.php
// @date: 20241013 18:30:25
// @desc: 
// @command: GDFONTPATH=($IGK_SITE_DEV_DIR)/.test/svg/fonts balafon --run .test/svg/svg-2-png.php --png --html
use igk\svg\SvgDocument;
use igk\svg\SvgDocumentRenderer;
use igk\svg\SvgUtil;
use IGK\System\Console\Logger;
use IGK\System\Drawing\Colorf; 

$help = property_exists($command->options, '--help');
$html = property_exists($command->options, '--html');
$png = property_exists($command->options, '--png');
if ($help) {
    echo 'convert svg to png data';
    igk_exit();
}
$file = igk_getv($params, 0);
if ($file) {
    Logger::success('done.');
}
$doc  = new SvgDocument;
$W = $H =  500;
$doc->setSize($W, $H, true);
$g = $doc->g();
$g->ellipse(100,50,$W / 2, $H / 2)->fill('red')->stroke('blue');
$s = $doc->render();
Logger::print($s);
if ($png) { 
    $g = new SvgDocumentRenderer;
    $cl = igk_web_colors(); 
    $cl = array_merge($cl, [
        "red" => [1, 0, 0]
    ]); 
    $g->setColorDef($cl);
    $doc->savePng('/tmp/out.png', $g); 
}
if ($html) {
    $hdoc = igk_create_node('html');
    $head = $hdoc->head();
    $head->title('SvgDocument');
    $head->style()->content = 'svg{border:1px solid black}';
    $body = $hdoc->body($s);
    $body['style'] = 'background-color: #cdcdcd;';
    $body->div()->addNode('img')->setAttributes([
        'style' => 'border: 1px solid black;',
        'src' => 'file:////tmp/out.png'
    ]);
    igk_io_w2file('/tmp/out.html', implode("\n", ['<!DOCTYPE html>', $hdoc->render()]));
}
Logger::success("open : file:////tmp/out.html");
igk_exit();