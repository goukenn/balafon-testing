<?php
// @command: balafon --run .test/css/get_file_content.php
use IGK\System\Html\Css\CssUtils;
use IGK\System\Html\Dom\HtmlDocTheme;
// $r = igk_array_key_map_implode(["one"=>1, "offert"=>2]);
// igk_wln_e(["result"=>$r]);
$doc = IGKHtmlDoc::CreateDocument('temp');
$th = $doc->getSysTheme();
$th->initGlobalDefinition();
$src  = CssUtils::GetInjectableStyleFromFileDefinition(__DIR__."/default.pcss", $ctrl, $th, $css, false);
igk_wln_e("src:" , $src, $th->getRootReference());