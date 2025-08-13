<?php
$x = "goudron";
?>
<<?= $x ?>>---<?= $x ?>8<?= $x ?></<?= $x ?>>
<?php
echo $x;
exit;
function str_insert(& $str, $data, $offset){
    $ln = strlen($str);
    $pos = $offset;
    $vln = strlen($data);
    $i = 0;
    while($i<$vln){
        if ($i+$offset < $ln){
            $str[$i+$offset] = $data[$i];
        }else {
            $str.= $data[$i];
        }
        $i++;
    }
}
$s = "Hello friend ... ";
$a = & $s ;
$a .= str_insert($s , "Jump", 10);
//can't create reference from string offset
$g = "Partir ";
$m = & $g[2];
$m.= "sample";
echo $g;