<?php
// @command: balafon --run .test/vue3/sfc.php
use igk\js\Vue3\Compiler\VueSFCCompiler;
use igk\js\Vue3\System\IO\VueSFCFile;

$file = new VueSFCFile;
$file->loadFile(__DIR__."/transition.vue");
$template = $file->template();
$src = VueSFCCompiler::ConvertToVueRenderMethod($template);
$file->renderAJX((object)['Indent'=>true]);
echo $src . PHP_EOL;
igk_exit();