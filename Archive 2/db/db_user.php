<?php
use IGK\Models\Users;
$user = Users::select_row(['clLogin'=>'cbondje@igkdev.com']);
$user->changePassword('admin@123');
$user->clStatus = 1;
$user->save();
echo igk_configs()->db_name . PHP_EOL;
echo json_encode($user->to_array()) . PHP_EOL;
exit;