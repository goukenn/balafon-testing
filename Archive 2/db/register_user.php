<?php
// @command: balafon --run .test/db/register_user.php
use IGK\Models\Users;
$l = WOHApiController::ctrl(true);
Users::Register([
    Users::FD_CL_LOGIN=>'dummy2@test.local.com'
], $l);