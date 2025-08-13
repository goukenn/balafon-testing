<?php
$tab = [8, 111,160];
// array map split la valeur en deux -- key valeur ! conntaire et trim la clef -- 
list($key, $value) =  array_map(function($i){
    return trim($i);
}, explode('=', " -  POM=SAMPLE --- "));
// igk_wln_e("data:", $p);
igk_wln_e(compact('key', 'value'));