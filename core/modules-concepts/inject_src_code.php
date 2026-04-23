<?php
// @command: balafon --run .test/core/modules-concepts/inject_src_code.php file
use IGK\System\Modules\ModuleIncludeDefinitionUtility;

$file = igk_getv($params, 0);
if (!realpath($file)){
    $file = __DIR__.'/'.$file;
}
file_exists($file) || igk_die('missing file');
class Invoker{
}
$invoc = new Invoker;
$src = file_get_contents($file);
$ref = [];
igk_environment()->set('debug_litteral', true);
$cache = ModuleIncludeDefinitionUtility::BindSourceFile($src, $file, $ref, []);
$b = igk_getv($cache, 'b');
igk_debug('litteral');
$b->bindTo($invoc);
$b();
igk_wln(json_encode($cache, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
igk_exit();