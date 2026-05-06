<?php
// @author: C.A.D. BONDJE DOUE
// @filename: split_litteral.php
// @date: 20260506 08:11:54
// @desc: split litteral
// @command: balafon --run .test/pdf/split_litteral.php
 

$params || igk_die('missing data');

$litteral = igk_getv($params, 0) ?? igk_die('no litteral');
$regex =  igk_getv($params, 1);
$assoc = igk_getv($params, 2);

igk_load_library('io');

$fn = igk_io_basenamewithoutext($litteral); 

# utilisation de variable exportation 
igk_wln_e('result: ', var_export(igk_io_split_litteral($fn, $regex, $assoc), true));