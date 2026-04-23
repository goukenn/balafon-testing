<?php

$ex = ['on'=>1];
try{
    throw new Exception('basic error');
}catch(\Exception $exm){
}
print_r($ex);
exit;