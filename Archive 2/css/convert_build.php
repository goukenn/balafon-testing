<?php
// @author: C.A.D. BONDJE DOUE
// @filename: convert_build.php
// @date: 20240912 07:16:41
// @desc: demo convert adn build css 
// @command: balafon --run .test/css/convert_build.php
use IGK\System\Console\Logger;
use IGK\System\Html\Css\Builder\ControllerLitteralBuilder;
$ctrl = AppTestProject::ctrl(true);
$g = new ControllerLitteralBuilder;
$g->controller = $ctrl;
$dir = __DIR__."/public/assets";
$g->outputDir = dirname($dir);
$g->outputFile = $dir."/css/main.css";
$g->build();
Logger::success($g->outputDir);
igk_exit();