<?php
// @author: C.A.D. BONDJE DOUE
// @filename: login_with_all.php
// @date: 20251207 16:55:30
// @desc: auth with all system user
// - login
// - pin
// - phonenumber
// @command: balafon --run .test/auth/login_with_all.php
use IGK\Models\Users;
define('igk_hook_find_user', IGKEvents::HOOK_FIND_USER);
$pin_user = [
    '0000'=>igk_get_user_bylogin('cbondje@igkdev.com')->clGuid
];
// igk_reg_hook(igk_hook_find_user, function($e) use($pin_user){
//     list($name) = igk_extract($e->args,'name');
//     if (isset($pin_user[$name])){
//         $guid = $pin_user[$name];
//         if ($r = Users::select_row([Users::FD_CL_GUID=>$guid])){
//             $e->output = $r;
//             $e->handle = true;
//         }
//     }
// });
// igk_reg_hook(igk_hook_find_user, function($e){
//    list($name) = igk_extract($e->args, 'name');
//    if ($name == '830804-495-20'){
//         $r = igk_get_user_bylogin('cbondje@igkdev.com');
//         $e->output = $r;
//         $e->handle = true;
//    } 
// });
// for a free use login system without password just identified the user on database then 
$name = igk_getv($params, 0) ?? igk_die('missing param [name}');
$user = igk_sys_find_auth_user($name) ?? igk_die('missing user');
$filter = igk_hook(IGKEvents::FILTER_LIST_AUTH_TYPE, []);
$print = [];
if ($user)
    $print[] = $user->to_json();
$print[] =  json_encode($filter, JSON_PRETTY_PRINT);
igk_wln_e( ...$print );