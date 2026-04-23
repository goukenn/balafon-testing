<?php
// @author: C.A.D. BONDJE DOUE
// @filename: login_with_all.php
// @date: 20251207 16:55:30
// @desc: auth with all system user
// @command: balafon --run .test/auth/login_with_all.php
use IGK\Models\Users;

define('igk_hook_find_user', IGKEvents::HOOK_FIND_USER);
$pin_user = [
    '0000'=>igk_get_user_bylogin('cbondje@igkdev.com')->clGuid
];
$name = igk_getv($params, 0) ?? igk_die('missing param [name}');
$user = igk_sys_find_auth_user($name) ?? igk_die('missing user');
$filter = igk_hook(IGKEvents::FILTER_LIST_AUTH_TYPE, []);
$print = [];
if ($user)
    $print[] = $user->to_json();
$print[] =  json_encode($filter, JSON_PRETTY_PRINT);
igk_wln_e( ...$print );