<?php
use IGK\Helper\StringUtility; 
use IGK\System\Console\Logger;
use IGK\System\IO\File\PHPScriptBuilder; 

$treat = igk_getv($params, 0);
if (!$treat){
    igk_die("require miss filename");
}
if (!is_file($treat)){
    $treat = getcwd()."/".$treat;
}
if (!is_file($treat)){
    Logger::danger("missing file : ".$treat);
    igk_exit();
}
$s = implode ("\n", array_filter(array_map(function($a){
    $a = trim($a);
    return 'const '.strtoupper(StringUtility::SanitizeIdentifier($a)).' = "'.ucfirst(strtolower($a)).'";';
},
explode("\n", file_get_contents($treat))
)));
$sb = new PHPScriptBuilder;
$sb->type('function')
->defs($s);
igk_wln_e($sb->render().'');
igk_exit();