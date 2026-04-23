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
$p = $n->render((object)['Indent' => false]);
$s = '<template><a href="#"><b><c><d>info</d></c><e></e></b></a></template>';
echo $p;
igk_exit();