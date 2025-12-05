<?php
// 
// VPS UTILITY 
//
// install zip of node x64
define('NODE_BIN', realpath('../../../../../core/Lib/node/bin/node'));  
echo "node running....."; 
$cmd  = NODE_BIN;
$ret = `{$cmd} --version`; 
echo $ret;