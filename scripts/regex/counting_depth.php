<?php

// @command: balafon --run .test/scripts/regex/counting_depth.php
/**
 * counting depth 
 * @param string $s 
 * @param ?string $tabstop auto dected start stop depth
 * @return int|float 
 */
function igk_count_depth($s, $tabstop=null){
    $regex = is_null($tabstop) ? '\\t|\s{4}' : $tabstop;
    $ref = preg_match("/^(".$regex.")\\1*/", $s, $tab);
    $c = 0;
    if ($ref){
        $ln = strlen($tab[1]);
        $c = strlen($tab[0]) / $ln;
    } 
    return $c;
}   
// echo igk_count_depth("\tbonjour")  == 1, "\n";
echo "with: tab\t" , igk_count_depth("\t\tbonjour")  == 2, "\n";
echo "with: space\t", igk_count_depth("        bonjour")  == 2, "\n";
igk_exit();