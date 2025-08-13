<?php
$g = 'prodr_U8AZser__  _Guid';
function get_constants_name($s, $splitter="/[A-Z0-9]+/"){
    $s = preg_replace("/[^a-z_0-9]/i", "", $s);
    $g = array_filter(explode("_",$s));
    $first = ucfirst($g[0]);
    $g = implode("_", array_filter(array_merge([$first], array_map(function($a)use($splitter){
        if ($p = preg_split("/[A-Z0-9]+/", $a, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_OFFSET_CAPTURE)){
            $ln = count($p);
            $g = ""; 
            for($i=0; $i<  $ln ; $i++){
                $v = $p[$i];
                $offset = $v[1];
                $next = ($i+1) < $ln ? $p[$i+1][1] : strlen($a); 
                $next = $next - $offset -strlen($v[0]);
                if ($r = substr($a, $offset+strlen($v[0]), $next)){
                    $r = '_'.$r;
                }
                if ($i==0){
                    $g.= substr($a,0,$offset);
                }
                $g .= $v[0].$r;
            } 
            $a = $g; 
        }
        return ucfirst($a);
     } , array_slice($g, 1)))));
    return strtoupper($g);
}
echo "Name : ".get_constants_name($g).PHP_EOL;
exit;