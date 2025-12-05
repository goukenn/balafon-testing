<?php
// balafon --run /Volumes/Data/Dev/PHP/balafon_site_dev/.test/vue3/sfc.php
use igk\js\Vue3\Compiler\VueSFCCompiler;
use igk\js\Vue3\System\IO\VueSFCFile;
$file = new VueSFCFile;
// basic test - 001 - normal generation 
// $file->template();
// $file->script()->activate('setup')->Content = 'console.log("ok");';
// $file->style()->Content = 'body{background-color:red;}';
// basic test - disable non allowed tag - expect exception 
// $file->div()->Content = "INFORMATION";
// $file->loadFile(__DIR__."/main.vue");
$file->loadFile(__DIR__."/transition.vue");
$template = $file->template();
$src = VueSFCCompiler::ConvertToVueRenderMethod($template);
$file->renderAJX((object)['Indent'=>true]);
echo $src . PHP_EOL;
igk_exit();