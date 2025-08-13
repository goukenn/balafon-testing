<?php
use com\igkdev\app\llvGStock\Groups;
use com\igkdev\app\llvGStock\Roles;
use com\igkdev\app\llvGStock\Profiles;
use IGK\Helper\Authorization;
$user = igk_get_user_bylogin('bondje.doue@igkdev.com');
// Attacher un utilisateur a un (profiles - group)
echo Authorization::BindUserToGroup($ctrl, $user , Groups::Administrator);
// echo Authorization::UnbindUserFromGroup($ctrl, $user , Groups::Administrator); 
echo "groups : " . PHP_EOL;
print_r($user->getGroupNames());
echo "auths : can change price ? " . PHP_EOL;
$data = $user->auth($ctrl::authName(Roles::ChangePrice));
print_r($user->auths());
print_r($user->getAuthorizationNames());
echo "PHP_DOD";
igk_wln_e("done : ".$data);