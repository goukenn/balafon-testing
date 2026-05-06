<?php


define('NODE_BIN', realpath('../../../../../core/Lib/node/bin/node'));  
echo "node running....."; 
$cmd  = NODE_BIN;
$ret = shell_exec("{$cmd} --version"); 
echo $ret;