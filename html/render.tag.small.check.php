<?php
use IGK\System\Html\HtmlNodeBuilder;

$n = igk_create_node('div');
$buidler = new HtmlNodeBuilder($n);
$buidler->preserveTagCase = true;
$buidler([
    'SamoleOk'=>'information'
]);
echo $n->render() .PHP_EOL;
$n = igk_create_node('div');
$n->load("<Simlink>simulation capitalize</Simlink>");
echo $n->render() .PHP_EOL;
exit;