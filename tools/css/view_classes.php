<?php
// + | --------------------------------------------------------------------
// + | detect bootstrap class declaration 
// + |
use IGK\System\Html\Css\CssClassNameDetector;
use IGK\System\Html\Css\CssParser;
$file = igk_getv($params, 0) ?? "/Volumes/Data/wwwroot/core/Projects/WatchCssDemo/node_modules/bootstrap/dist/css/bootstrap.css";
$src = file_get_contents($file);
$parser = CssParser::Parse($src);
$detector = new CssClassNameDetector;
$detector->map($parser->to_array());
$tab = array_keys($detector->list);
sort($tab);
echo implode("\n", $tab); 
igk_exit();