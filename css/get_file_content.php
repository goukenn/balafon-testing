<?php
// @command: balafon --run .test/css/get_file_content.php
use IGK\System\Html\Css\CssUtils;
use IGK\System\Html\Dom\HtmlDocTheme;
// $r = igk_array_key_map_implode(["one"=>1, "offert"=>2]);
// igk_wln_e(["result"=>$r]);
$doc = IGKHtmlDoc::CreateDocument('temp');
$th = $doc->getSysTheme();
//$th->parent
$th->initGlobalDefinition();
$src  = CssUtils::GetInjectableStyleFromFileDefinition(__DIR__."/default.pcss", $ctrl, $th, $css, false);
// $th = new HtmlDocTheme($doc, 'temp-style');
// // igk_css_bind_sys_global_files($th->parent);
//     // public function initGlobalDefinition(){
// $th->parent->initGlobalDefinition();
// $css = CssUtils::GetFileContent(__DIR__."/default.pcss", $ctrl, $th );
// $src = $th->get_css_def(true,false);
// $th->parent->resetSysGlobal();
$srcc = 'bgcl:var(--igk-hpane-background-color, #444);';
igk_wln_e("src: " , $src, $th->getRootReference());