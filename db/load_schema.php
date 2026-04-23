<?php
use IGK\Database\DbSchemas;
use IGK\System\Console\Logger;

$fb = igk_require_module('igk\social\facebook');
list($file) = $params; 
if ($r = DbSchemas::LoadSchema($file)){ 
    print_r(array_keys((array)$r->tables["tbigk_products"]->columnInfo)); 
}else{
    Logger::danger("failed");
}
igk_exit();