<?php
// @command: balafon --run .test/array_map.php
// + | a vary to split data list 
// + | array_map demonstraction

$tab = [8, 111,160,180, 'login'];
list($key, $value, , $data) =  array_map(function($i){
    return trim($i);
}, $tab); 
igk_wln_e(compact('key', 'value'));