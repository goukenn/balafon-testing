<?php
use IGK\System\Console\Logger;

list($uid, $pwd) = $params;
if ($pwd && ($user = \IGK\Models\Users::select_row($uid))){
    $user->clStatus = 1;
    if ($s = $user->changePassword($pwd)){
        print_r($user->to_array());
    }
    Logger::info("password changed: ". $s);
} else 
Logger::danger("user missing");