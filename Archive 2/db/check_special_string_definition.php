<?php
use IGK\Controllers\SysDbController;
use IGK\System\Console\Logger;
$sql = 'SELECT * from tbigk_users where clLogin = \'\';';
// $sql = '😄';
$db = SysDbController::ctrl();
// replace special char ok 
// $sql = str_replace('😄', '_', $sql);
// special character is u
echo strlen($sql), PHP_EOL;
if (strlen($sql) != mb_strlen($sql, 'UTF-8')){
    echo "contain extra character ";
    // while(strlen($sql))
    // echo "base: ".$sql; 
    // exit;
    $sql = mb_convert_encoding($sql, 'UTF-8');
}
// $sql = mb_strlen($sql, 'UTF-8');
if ($ad = $db->getDataAdapter()){
    $ad->connect();
    $ad->sendQuery('ALTER TABLE table_name MODIFY field_name TEXT CHARSET utf8mb4;');
    $ad->sendQuery($sql);
    $ad->close();
}
Logger::print($sql);
igk_exit();