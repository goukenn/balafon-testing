<?php
use IGK\System\Html\HtmlNodeBuilder;
$n = igk_create_notagnode();
igk_debug(true);
$d = new HtmlNodeBuilder($n);
$d([ 
    'ul'=>[
        'li > a[to:/]'=>'Home', 
        'li > b[to:/about]'=>'About',
    ]  
], $n->addNode('template'));
// $n->script()->activate('setup');
// $n->style()->activate('module')->Content = <<<CSS
// .page{background-color: #222; color: #fefefe;}
// CSS;

$p = $n->render((object)['Indent' => false]);
$s = '<template><a href="#"><b><c><d>info</d></c><e></e></b></a></template>';

echo $p;
igk_exit();
