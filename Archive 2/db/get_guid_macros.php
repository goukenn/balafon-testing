<?php
use com\igkdev\bantubeat\Models\Users;
use com\igkdev\bantubeat\Profiles;
use IGK\Helper\Authorization;
$ctrl = bantubeatController::ctrl();
$ctrl::register_autoload();
echo Users::select_all(null, ['Limit',1])[0]->guid();
$user =  igk_get_user_bylogin('cbondje@igkdev.com');
Authorization::BindUserToGroup($ctrl, $user, Profiles::Admin);
print_r($user->groups());
exit;