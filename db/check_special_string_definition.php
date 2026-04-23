<?php
use IGK\Controllers\SysDbController;
use IGK\System\Console\Logger;

$sql = 'SELECT * from tbigk_users where clLogin = \'\';';
$db = SysDbController::ctrl();
echo strlen($sql), PHP_EOL;
if (strlen($sql) != mb_strlen($sql, 'UTF-8')){
    echo "contain extra character ";
    $sql = mb_convert_encoding($sql, 'UTF-8');
}
if ($ad = $db->getDataAdapter()){
    $ad->connect();
    $ad->sendQuery('ALTER TABLE table_name MODIFY field_name TEXT CHARSET utf8mb4;');
    $ad->sendQuery($sql);
    $ad->close();
}
Logger::print($sql);
igk_exit();