<?php
// @command: balafon --run .test/db/authorizations.php
use IGK\Controllers\BaseController;
use IGK\Database\Helper\DbInitManagement;
use IGK\Models\Authorizations;
use IGK\Models\Groupauthorizations;
use IGK\Models\Groups;
use IGK\Models\Usergroups;
use IGK\System\Console\Logger;

$ctrl = ForemJobDashboardController::ctrl(true);
Authorizations::registerMacro('for', function(BaseController $ctrl){
    return $this->select_all([
       Authorizations::FD_CL_CONTROLLER=>$ctrl::keyName()
    ]);
});
$ad = Authorizations::model()->getDataAdapter();
$auths = Authorizations::for($ctrl);
$c = [];
foreach($auths as $row){
    $c[] = $row->clId;
}
if ($c){
    $query = sprintf('delete From `%s` WHERE clAuth_Id IN(%s);', Groupauthorizations::table(), implode(',',$c));
    Logger::info('delete : '. $ad->sendQuery($query));
}
$c = [];
foreach(Groups::select_all($cond = [
    Groups::FD_CL_CONTROLLER=>$ctrl::keyName()
]) as $row){
    $c[] = $row->clId;
}
if ($c){
    $query = sprintf('delete From `%s` WHERE clGroup_Id IN(%s);', Usergroups::table(), implode(',',$c));
    Logger::info('delete : '. $ad->sendQuery($query));
}
Groups::delete($cond);
DbInitManagement::InitControllerProfile($ctrl,false);
Logger::success('done');
igk_exit(0);