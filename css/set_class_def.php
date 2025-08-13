<?php
// @command: balafon --run .test/css/set_class_def.php
$n = igk_create_node('div');
$n['class'] = 'fitw.fit no-overflow posab';
$s = $n->render();
($s == '<div class="fitw fit no-overflow posab"></div>') || igk_die('failed : '.$s);
echo $s;
igk_exit();