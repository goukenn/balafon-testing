<?php
// balafon --run .test/test_login.php
use com\igkdev\bantubeat\CommunityManagerProfiles;
use com\igkdev\bantubeat\Models\CommunityManager;
use com\igkdev\bantubeat\Models\Users as ModelsUsers;
use com\igkdev\bantubeat\Profiles;
use IGK\Helper\Authorization;
use IGK\Models\Users;
use IGK\System\Console\Logger;
bantubeatController::register_autoload();
$ctrl = bantubeatController::ctrl();
$u = igk_get_user_bylogin('bondje.doue@igkdev.com');
$u->clPwd = 'admin@123';
$u->clStatus = 1;
$u->save();
Authorization::BindUserToGroup($ctrl, $u, Profiles::Admin);
$u1 = ModelsUsers::select_row(1);
$u2 = ModelsUsers::select_row(['usr_guid'=>$u->clGuid]);
if ($u1 && $u2){
CommunityManager::BindToUser($u1, $u2, CommunityManagerProfiles::COMMR_PROFILEADMIN ) ;
Logger::info("check if boundto");
$r = CommunityManager::IsBoundToUser($u1, $u2);
igk_wln_e("edit beat  = ", $r->to_json(null, JSON_PRETTY_PRINT));
}
// $g = Users::select_row(["clGuid"=>"{B2060D35-EDBC-0505-7AA6-CF49B17DEB84}"]);
// $g->clStatus = 1;
// $g->save();
$g = $ctrl->login("bondje.doue@igkdev.com", 'admin@123');
igk_wln_e("g : ", $g);