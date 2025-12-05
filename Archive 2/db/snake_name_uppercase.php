<?php
use IGK\Helper\StringUtility;
function igk_str_snake2($str){
    $g = $str;
    $p = preg_split("/[A-Z]+/", $str,-1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_OFFSET_CAPTURE |
     PREG_SPLIT_DELIM_CAPTURE);
    if ($p){
        $g = "";
        $ln = count($p);
        for($i=0; $i<  $ln ; $i++){
            $v = $p[$i];
            $offset = $v[1];
            $next = ($i+1) < $ln ? $p[$i+1][1] : strlen($str); 
            $next = $next - $offset -strlen($v[0]);
            $g .= $v[0]. '_'. substr($str, $offset+strlen($v[0]), $next);
        } 
        $g = trim($g, "_");
    } 
    $g = array_map('ucfirst', array_map('strtolower', array_filter(explode("_", $g))));
    return implode('_', $g);
}
$src = 'btbccr_idA45_dsdfd';
echo 'source : '.$src.PHP_EOL;
$e =  igk_str_snake($src);
echo $e.PHP_EOL;
$e =  igk_str_snake2($src);
echo $e.PHP_EOL;
igk_exit();