<?php
// # change user password - 
//@command : balafon --run .test/db/change_user_pwd.php uid pwd
use IGK\System\Console\Logger;
list($uid, $pwd) = $params;
if ($pwd && ($user = \IGK\Models\Users::select_row($uid))){
    // $user->clPwd = $pwd;
    $user->clStatus = 1;
    if ($s = $user->changePassword($pwd)){
        print_r($user->to_array());
    }
    // $s = $user->save();
    Logger::info("password changed: ". $s);
} else 
Logger::danger("user missing");