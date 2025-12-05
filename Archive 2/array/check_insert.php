<?php
$t1 = ['a'=>2, 'b'=>5, 4, 'c'=>'pl'];
$t2 = ['user'=>'1', 'login'=>'base'];
// $pos = array_search('b', array_keys($t1));
$pos = array_search(0, array_keys($t1));
$t3 = array_slice($t1, 0, $pos+1, true ) + $t2 +  array_slice($t1, $pos, count($t1)-$pos, true );
print_r($t3);