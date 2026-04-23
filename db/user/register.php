<?php
  // @command: balafon --run .test/db/user/register.php
use IGK\Models\Users;

$r = Users::Register([
    'clLogin'=>'goukennra',
    'clPwd'=>'admin123'
 ]);
 igk_wln_e($r);