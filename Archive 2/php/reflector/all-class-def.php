<?php
// @author: C.A.D. BONDJE DOUE
// @filename: all-class-def.php
// @date: 20250723 06:51:02
// @desc: get all call def documentation
// @command: balafon --run .test/php/reflector/all-class-def.php

use IGK\System\Console\Logger;
use igk\tools\Reflector\Helpers\Harmonize; 
use function igk\tools\Reflector\treat_files;
$eol = PHP_EOL;
$dir = igk_getv($params, 0) ?? __DIR__.'/data';  
$options = (object)[
    'noCode'=>true,
    'trimEmptyLine'=>true,
];
$s = treat_files($dir, $options); 

echo json_encode($s, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES), $eol;

if ($s){
    Logger::warn($s->global_script->output.'');
}

if ($s && $s->files){
    Logger::info('-******************** harmonize ********************-');
    echo Harmonize::Render(igk_getv(array_values($s->files), 0), $s), $eol;
}
igk_exit();

