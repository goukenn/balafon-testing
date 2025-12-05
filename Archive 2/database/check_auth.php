<?php
use IGK\Helper\Authorization;
$ctrl = CarRentalController::ctrl();
$user = igk_get_user_bylogin('bondje.doue@igkdev.com');
igk_environment()->querydebug = 1;
Authorization::BindUserToGroup($ctrl, $user, 'Admin');
$o = $user->auths();
$groups = $user->groups();
igk_wln_e($o, $groups);