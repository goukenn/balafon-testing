<?php
// @author: C.A.D. BONDJE DOUE
// @filename: svg-convert-lib.php
// @date: 20241001 08:48:20
// @desc: test convert to js library 
use igk\js\Vue3\System\Console\Commands\Svg\ConvertSVGToVueCommand;
use IGK\System\Console\Logger;
igk_require_module('igk\js\Vue3');
igk_is_debug() && Logger::info('convert list');
$r = ConvertSVGToVueCommand::ConvertSvgToJsLibrary([
    'ionicons'=>['/Volumes/Data/Documents/IonIcons/ionicons/src/svg', 'accessibility,add'],
    'sfsymbols'=>['/Volumes/Data/Documents/SfSymbols/symbols', 'eraser,folder.fill,sunset']
], false);
echo $r , "\n";
Logger::offscreen()->success('done');
igk_exit();