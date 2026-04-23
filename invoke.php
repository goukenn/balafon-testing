<?php

$a = " information " ?>  Information<?php ; 
$get_tokens = implode("\n", array_slice(explode("\n", file_get_contents(__FILE__)), 0, __LINE__ - 1));
foreach(token_get_all($get_tokens) as $e){
    if (is_array($e)){
        echo "token: ".token_name($e[0]).":".$e[1]."\n";
    }
}
echo "the a value : ".$a;