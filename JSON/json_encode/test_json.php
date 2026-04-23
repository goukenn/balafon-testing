<?php 
use IGK\Helper\JSon; 

$s = JSon::Encode(['a'=>4, 'b'=>null, 'm'=>[5,9,null,9], 
    'c'=>(object)['x'=>(object)['jj'=>[111,null,(object)['x'=>444, 'y'=>null]]], 'y'=>null, 'xx'=>88],
    'x'=>554
    ], [
    'ignore_empty'=>true
]);
igk_wln_e('response : '.$s.' '.PHP_EOL);