<?php
// @author: C.A.D. BONDJE DOUE
// @filename: engine_detection.php
// @date: 20250817 13:30:21
// @desc: engine detection
// @command: balafon --run .test/modules/igk.phpFormatter/engine_detection.php

 
use IGK\System\Console\Logger;
use IGK\System\Text\Formatters\IFormatterService;

$name = igk_getv($params, 0, sprintf('%s.html', IGKServices::FORMATTER_SERVICE));
$srv = igk_app()->getService($name);

if ($srv instanceof IFormatterService) {
    echo 'engine class : ' . $srv->engineClassName, PHP_EOL;
    $src = '<div>information.     avec toutes <span>les don </span>nnées du jour </div>';
    $osr = $srv->format($src);  
    igk_wln(json_encode(compact('src', 'osr'), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}
Logger::success('done');
igk_exit();
