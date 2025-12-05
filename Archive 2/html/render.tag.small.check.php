<?php
use IGK\System\Html\HtmlNodeBuilder;
$n = igk_create_node('div');
$buidler = new HtmlNodeBuilder($n);
$buidler->preserveTagCase = true;
// transform by default tag to lower case .
$buidler([
    'SamoleOk'=>'information'
]);
echo $n->render() .PHP_EOL;
//load preserve capitalization 
$n = igk_create_node('div');
$n->load("<Simlink>simulation capitalize</Simlink>");
echo $n->render() .PHP_EOL;
exit;