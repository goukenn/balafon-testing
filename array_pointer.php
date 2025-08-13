<?php
// a, b, c- cha
use igk\io\GraphQl\GraphQL;
use igk\io\GraphQl\GraphQlPointerObject;
$a = [1,2,3];
$b = [$a];
$c = & $b[0];
$c[] = 8;
$b[0] = null;
// $a = null;
$b[] = 6;
igk_wln($b, $a, $c);
// good way to get object 
$a = [];
$q = ["obj"=>& $a,"p"=>null];
$m = ["obj"=>& $m, "p"=>$q];
// modify reference directly
$q['obj'][] = 13;
// copy object - not reference 
$r = $q['obj'];
$r[] = 55;
igk_wln("r ;;; ", $r);
// get reference 
$o = & $m['p']['obj'];
$o['x'] = 'DS';
$m["p"]['obj'][] = 6;
igk_wln("-----");
igk_wln($a);
igk_wln("checing pointer .... ");
require_once '/Volumes/Data/wwwroot/core/Packages/Modules/igk/io/GraphQl/Lib/Classes/GraphQlPointerObject.php';
$root = [];
$obj = new GraphQlPointerObject($root);
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
$obj2 =  new GraphQlPointerObject($root[count($root)-1], $obj);
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
// free pointer 
//unset($root);
//$root = null;
// clear pointer data 
// $root = [];
$obj->unset();
//$root = & [1][0];
igk_wln_e("define : ", $obj->getRefData(), 'root:', $root, "c:" , $c);
print_r($root);
print_r($mm);
igk_wln("---------------------------");
$root = $obj->getRefData();
print_r($root);
print_r($mm);