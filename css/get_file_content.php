<?php
// @command: balafon --run .test/css/get_file_content.php
use IGK\System\Html\Css\CssUtils;
use IGK\System\Html\Dom\HtmlDocTheme;

$doc = IGKHtmlDoc::CreateDocument('temp');
$th = $doc->getSysTheme();
$th->initGlobalDefinition();
$src  = CssUtils::GetInjectableStyleFromFileDefinition(__DIR__."/default.pcss", $ctrl, $th, $css, false);
igk_wln_e("src:" , $src, $th->getRootReference());