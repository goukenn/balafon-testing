<?php
use igk\js\Vue3\Compiler\VueSFCCompiler;
use igk\js\Vue3\Compiler\VueSFCCompilerOptions;
use igk\js\Vue3\Compiler\VueSFCRenderNodeVisitorOptions;
use igk\js\Vue3\Components\VueComponent;
use igk\js\Vue3\Components\VueNoTagNode;
use IGK\System\Html\Dom\HtmlNode;
use IGK\System\Html\HtmlNodeBuilder;

$d = new VueNoTagNode('div');
$builder = new HtmlNodeBuilder($d);
$d->div()->vIf('a')->Content = 'a';
$d->div()->vIf('b')->Content = 'b';
$d->clearChilds();
/**
* auto generate doc.
* @return mixed
*/
function igk_html_node_base_param()
{
    return new VueComponent('div-b');
}
$d = new HtmlNode('div');
$d->load(<<<'HTML'
<div>
    <router-link to="/proposal" class="igk-btn btn custom-btn nav-btn"> '{{ $t('Propose a car') }} </router-link>
</div>
HTML, []);
$options = new VueSFCRenderNodeVisitorOptions;
$options->components = array_fill_keys(['CustomItem'], 1); 
$src = VueSFCCompiler::ConvertToVueRenderMethod($d, $options);
igk_wln_e($src);