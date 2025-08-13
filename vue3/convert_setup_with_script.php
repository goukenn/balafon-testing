<?php
igk_require_module(igk\js\Vue3::class);
use igk\js\Vue3\Compiler;
use igk\js\Vue3\Compiler\VueSFCCompiler;
use igk\js\Vue3\Compiler\VueSFCCompilerOptions;
$compiler = new VueSFCCompilerOptions;
$compiler->export = true;
$compiler->resourceResolver = function($lib){
    if (strpos($lib, '@')===0){
        $lib = ltrim($lib, '@');
        return "../".ltrim($lib, '/');
    } 
    igk_wln_e("resolver..... ".$lib);
};
$vue = igk_create_notagnode();
$vue->load(file_get_contents(__DIR__ ."/demo.vue"));
if ($scripts = $vue->getElementsByTagName("script")){
    $script_info = (object)[
        "inject"=>"",
        "setup"=>"",
        "return"=>""
    ];
    foreach ($scripts as $sc) {
        # code...
        $txt  = $sc->getInnerHTML();
        if ($sc->isActive("setup")){
            $g = VueSFCCompiler::GetLitteralSetupScript($txt, null, $compiler);
            igk_wln_e("setup....", $g);
        }
    }
}
// $vue->renderAJX();
exit;