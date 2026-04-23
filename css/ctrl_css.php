<?php
// @command: balafon --run .test/css/ctrl_css.php
use IGK\System\Console\Logger;
use IGK\System\Html\Css\CssControllerStyleRenderer;

$ctrl = igk_default::ctrl();
require_once IGK_LIB_DIR . '/Styles/igk_css_colors.phtml';
$th = $ctrl->getDoc()->getTheme();
$th->setColors([
    '--igk-bg-cl'=>'white',
]);
$th->setThemeColors([
    'dark'=>[
        '--igk-bg-cl'=>'white',
        '--igk-r-bg-cl'=>'orange'
    ],
    'light'=>[
        '--igk-bg-cl'=>'indigo',
        '--igk-r-bg-cl'=>'lime'
    ],
]);
$m = new CssControllerStyleRenderer;
$m->noCoreStyleDefinition = true;
$m->ctrl = $ctrl;
$m->doc = $ctrl->getCurrentDoc();
$g = $m->output(); 
Logger::print($g->render().'');
Logger::success("end script done");
igk_exit();