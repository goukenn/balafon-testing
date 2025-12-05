<?php
// attention a la surcharge d'opérateur - si variable ex modifier par le scope Exception celui remplace
$ex = ['on'=>1];
try{
    throw new Exception('basic error');
}catch(\Exception $exm){
}
print_r($ex);
exit;