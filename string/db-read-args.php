<?php
// @author: C.A.D. BONDJE DOUE
// @filename: db-read-args.php
// @date: 20251226 13:04:50
// @desc: string read args
// @command: balafon --run .test/string/db-read-args.php
use IGK\Helper\StringDisplay; 
use IGK\System\Console\Logger;
$properties = explode('|','name|firstname|lastname|login');
$display = 'info is name, "=", lastname';
$row = (object)[
    'lastname'=>'BONDJE DOUE'
];
// for model 
$row = igk_get_user_bylogin('cbondje@igkdev.com');
$properties = array_keys($row->to_array());
$display = 'Hi!,", ", :clLogin, " [ ", ^clFirstName, " ] " ';
$l = StringDisplay::Display($display, $properties, $row);
Logger::info('success : '.$l);
Logger::success('done');
igk_exit();