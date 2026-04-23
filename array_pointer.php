<?php
use igk\io\GraphQl\GraphQL;
use igk\io\GraphQl\GraphQlPointerObject;

$module = igk_require_module('igk\\io\\GraphQl');
$a = [1,2,3];
$b = [$a];
$c = & $b[0];
$c[] = 8;
$b[0] = null;
$b[] = 6;
igk_wln($b, $a, $c);
$a = [];
$q = ["obj"=>& $a,"p"=>null];
$m = ["obj"=>& $m, "p"=>$q];
$q['obj'][] = 13;
$r = $q['obj'];
$r[] = 55;
igk_wln("r ;;; ", $r);
$o = & $m['p']['obj'];
$o['x'] = 'DS';
$m["p"]['obj'][] = 6;
igk_wln("-----");
igk_wln($a);
igk_wln("checing pointer .... ");
require_once $module->getClassesDir().'/GraphQlPointerObject.php';
$root = [];
$obj = new GraphQlPointerObject($root);
/**
* auto generate doc.
* @param mixed & $obj
*/
function update(& $obj){
    $obj[] = 1;
    $obj[] = -1;
}
update($obj->getRefData());
$root[] = 9;
update($obj->getRefData());
$root[] = [];
igk_wln("last index ", array_key_last($root));
$c =  $root[count($root)-1];
$tag = [];
$obj2 =  new GraphQlPointerObject($tag, $obj);
$c[] = 5;
update($obj2->getRefData());
$mm = & $obj2->getRefData();
$mm[] = 'OIO';
$ctu = $obj2->getParentRef();
if ($ctu){
update($ctu->getRefData());
if ($obj===$ctu){
    igk_wln("okd...");
}
}
$obj->unset();
igk_wln_e("define : ", $obj->getRefData(), 'root:', $root, "c:" , $c);
print_r($root);
print_r($mm);
igk_wln("---------------------------");
$root = $obj->getRefData();
print_r($root);
print_r($mm);