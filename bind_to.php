<?php
// + | --------------------------------------------------------------------
// + | test binding to group
// + |
use com\igkdev\app\llvGStock\Roles;
use IGK\Helper\Authorization;
$user = $ctrl->getUser()->sys_user();
if (!$user){
    igk_die("user missing");
}
$skey = "Reseller";
$g = Authorization::BindUserToGroup($ctrl,
$user,
$skey
);
 // remove check for authorization
$name = $ctrl::authName( Roles::RmProduct);
igk_wln_e("is auth ",  $name, 
 $user->auth($name), 
);