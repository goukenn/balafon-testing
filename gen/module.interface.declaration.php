<?php
// @command: balafon --run .test/gen/module.interface.declaration.php
use IGK\System\Console\Logger;
use IGK\System\IO\File\PHPScriptBuilder;
use IGK\System\IO\StringBuilder;
use IGK\System\Modules;
/**
 * retrieving name 
 * @param string $n 
 * @return string 
 */
function method_def(string $n): string
{
    $n = str_replace("\\", "_", $n);
    $n = str_replace("/", "_", $n);
    return $n;
}
$l = igk_get_modules();
usort($l, function($a, $b){
    return strcasecmp(strtolower($a->name), strtolower($b->name));
});
$php = new PHPScriptBuilder;
$sb = new StringBuilder;
foreach ($l as $k) {
    $n = method_def($k->name);
    $sb->appendLine(sprintf('@method static string %s() %s', $n, igk_getchainv(
        $k,
        'description',
        sprintf('name of %s', $k->name)
    )));
}
$php->type('interface')
    ->namespace('IGK\\System')
    ->name('IModuleDefinition')
    ->phpdoc($sb . '')
    ->comment('auto generated document for modules definitions');
igk_io_w2file($f = IGK_LIB_CLASSES_DIR . "/System/auto_inc.modules.php", $src = $php->render());
// IGKEvents::HOOK_ON_INSTALL;
// IGKEvents::HOOK_ON_MODULE_ADDED;
igk_wln($src);
Logger::success("done: " . $f);
igk_wln_e('');