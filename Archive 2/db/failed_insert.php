<?php
use IGK\Models\Users;
$query = "INSERT INTO `tbigk_users`(`clLogin`, `clGuid`) VALUES ('cbondje@igkdev.be', 'base');";
$lref = Users::model()->getDataAdapter();
echo $query . PHP_EOL;
$q = $lref->sendQuery($query);
igk_wln_e($q);