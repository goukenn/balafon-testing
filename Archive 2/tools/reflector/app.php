<?php
// @author: C.A.D. BONDJE DOUE
// @filename: app.php
// @date: 20250702 08:39:57
// @desc: use to create libraries documentation 
// @command: balafon --run .test/tools/reflector/app.php .test/tools/reflector/temp
namespace igk\tools\Reflector;

use IGK\System\Console\Logger;
use igk\tools\Reflector\Helpers\Harmonize;
$input = igk_getv($params, 0) ?? igk_die('missing parameter');
$output = igk_getv($params, 1) ?? __DIR__ . '/output';
use function igk\tools\Reflector\treat_files;

$s =treat_files($input);
if (igk_is_debug()){
   echo json_encode($s, JSON_PRETTY_PRINT| JSON_UNESCAPED_SLASHES), PHP_EOL;
}
// + | ------------------------------------------------------------------------------
// + | 
// + | 

if (isset($s->global_script->output)){
    Logger::info('script: '. $s->global_script->output);
}
if ($s && $s->files){
    Logger::info('harmonize');
    echo Harmonize::Render(igk_getv(array_values($s->files), 0), $s);
}
igk_exit();