<?php
// balafon --run .test/dump/decode_unicode.php
$v = "{\"x\": \"VALORISATION \\\"avec\\\" DE MAT\u00c9RIELS\"}"; // quotes string 
 $v = '{"x": "VALORISATION \"avec\" DE MAT\u00c9RIELS"}'; // single quote string
if (strpos($v, '\\u') !== false){
    $v = addslashes($v);  
} 
$v = stripslashes($v);
$p = json_decode($v,true, 512);
echo "error : ".json_last_error_msg();
print_r($p);
igk_wln_e("decoding .. ", $p);