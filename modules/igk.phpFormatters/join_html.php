<?php
// @command: balafon --run .test/module/igk.phpFormatter/join_html.php
use IGK\System\Html\HtmlRenderer;
use function igk_html_host as _h;

/**
* auto generate doc.
* @param mixed $s
* @param mixed $g
*/
function render_ecap($s,$g){
    $t = $s->getTagName();
    if ($attr = HtmlRenderer::GetAttributeString($s, null)){
        $attr = ' '.$attr;
    }    
    if ($s->isEmptyTag()) {
        return sprintf("<%s%s/>",$t,$attr).$g;
    }
    return sprintf("<%s%s>%s</%s>", $t, $attr, $g, $t);
}
echo render_ecap(_h('link.line'), 'sublime');
igk_exit();