<?php
// @command: balafon --run .test/bcss/export_bcss_theme_definition.php

use IGK\System\Console\App;
use IGK\System\Html\Css\CssMinifier;



$th = igk_app()->getDoc()->getSysTheme();
igk_start_time($_ck = '_global_def_time');
$th->initGlobalDefinition();
$l = igk_execute_time($_ck);

igk_start_time($_ck = '_global_def_render');
$r = $th->get_css_def(true);
$rl = igk_execute_time($_ck);

if (property_exists($command->options, '--minify')) {
    Logger::info('minify... css');
    $css_minifier = new CssMinifier;
    $r = $css_minifier->minify($r);
}

if (property_exists($command->options, '--report')) {
    echo implode("\n", ['definition : ', $r]);
    echo App::BLUE;
    echo  json_encode(['time:' => $l, 'render_time:' => $rl], JSON_PRETTY_PRINT);
    echo App::END;
} else {
    echo $r, PHP_EOL;
}
igk_exit();
