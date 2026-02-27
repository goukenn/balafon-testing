<?php
// @author: C.A.D. BONDJE DOUE
// @filename: check-init-param.php
// @date: 20251024 08:32:48
// @desc: check initial parameter for noode creationg 
// @command: balafon --run .test/html/dom/check-init-param.php
use IGK\System\Console\Logger;
use IGK\System\Html\Dom\HtmlItemBase;
igk_wln('check run file:');
foreach($tb = ['content', [
    'title'=>'sample',
    'Content'=>'litteral sample'
]] as $r){
    $n = igk_create_node('div');
    $n->dummy($r);
    Logger::print($n->render());
}
// enregistrement de package
igk_reg_component_package('goukenn', function($n){
    igk_wln('create component: '.$n);
}, 'dummy gouken package');
// + register a component with callback definition 
igk_reg_html_component('gouken_card', function($l=null, $alt=null){
    $n = igk_create_node_arg('div.gouken-card');
    if ($l) 
        HtmlItemBase::BindDefaultContent($n, $l);
    if($alt){
        $n->setAttribute('alt', $alt);
    }
    return $n;
});
Logger::info('initialize ...');
foreach($tb as $k){
    $n = igk_create_node('div');
    // $n->add('goukenn:gouken_card', $k);
    $n->add('gouken_card',null, [$k, 'alt'=>'spacial']);
    //$n->gouken_card($k);
    Logger::print($n->render());
}
Logger::success('done');
igk_exit();