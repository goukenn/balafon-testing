<?php
// @command: balafon --run .test/html/components/with-required-script.php
use IGK\System\Html\CallableConstants;
function igk_html_node_demo_inject()
{
    $n = igk_create_node('div');
    // + | unique method for rendering object 
    $id = __FUNCTION__.':/script';
    // * how to auto inject only on redering on document.
    $n->setCallback(CallableConstants::CALLABLE_ACCEPT_RENDER, function ($node, $options)use($id) { 
        $visible = $node->AcceptRender($options);
        if (!$visible){
            return false;
        }
        if ($doc = $options->Document) {
            $injector =  $doc->DocumentInjector;  
            if (!$injector->contains($id)) {  
                $injector->register($id, function (&$content) use ($node, $doc) {
                    $script = igk_create_node('script');
                    $script->Content = '/* Loading  injected */';
                    $content .= $script->render();
                });
            }
        } else if ($options->context == 'AJX') {
            igk_wln_e('on ajx context ');
        }
        return true;
    });
    return $n;
}
$doc = IGKHtmlDoc::CreateDocument('sample', [
    'noCoreScript'=>true,
    'noCoreCss'=>true,
    'viewport'=>'width=>device-width, inital-scale=1, maximum-scale=1.0, use-scalable=no'
]);
// $doc->getMetas()->set
$c = igk_html_node_demo_inject();
$c->setIsVisible(false);
$body = $doc->getBody();
$body->add($c);
$c = igk_html_node_demo_inject();
$c->setIsVisible(false);
$body->add($c);
$doc->renderAJX();
igk_exit();