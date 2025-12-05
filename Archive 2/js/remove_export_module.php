<?php
// @desc : remove global export from source file demonstration 
use IGK\System\Console\App;
use IGK\System\Html\Dom\HtmlScriptLoader;
$src =<<<JS
(function(){
    return {
        x: 7,
        export(){
            return { x: 1}
        }
    };
})();
export const label = 8;
console.log('sample export '); 
export {
    data: 8,
    info : 9
}
JS; 
$src = file_get_contents('/Volumes/Data/Dev/PHP/balafon_site_dev/src/application/Lib/igk/Scripts/system/text/RegexContainer.d.js');
// remove global sciprt loading 
$g = HtmlScriptLoader::RemoveGlobalExportFromContent($src);
igk_wln_e( App::Gets(App::AQUA, "out : "), $g);