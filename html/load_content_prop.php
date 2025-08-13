<?php
use IGK\Controllers\SysDbController;
use IGK\Database\DbSchemas;
use IGK\System\Console\Logger;
use IGK\System\Html\HtmlReader;
use IGK\System\IO\Path;
$src = <<<'HTML'
<div sample
    = "44"
    X
    y>
    x</div>
HTML;
$d = igk_create_node('p');
$d->load($src);
$file = Path::CombineAndFlattenPath(__DIR__,'./load_content.db.chema.xml');
// $xcode = HtmlReader::LoadFile($file);
$xcode = HtmlReader::Load($src);
igk_wln_e($xcode->render(), $d->render());
Logger::print($d->renderAJX());
Logger::print(str_repeat("-", 10));
$ctr= SysDbController::ctrl(true);
$g = DbSchemas::LoadSchema(Path::CombineAndFlattenPath(__DIR__,'./load_content.db.chema.xml'), $ctrl);
echo json_encode($g, JSON_PRETTY_PRINT);
igk_wln_e("ggggg", $g);
igk_exit();