<?php
// @command: balafon --run .test/db/clear-reference-tab.php
use IGK\Models\Groups;
use IGK\Models\Usergroups;
use IGK\System\Database\SQLGrammar;

$ctrl = ForemJobDashboardController::ctrl(true);
$driver = $ctrl->getDataAdapter();

$query = igk_str_format('DELETE FROM `{0}` WHERE {2} IN (SELECT `{3}` FROM `{1}`{4});', Usergroups::table(), 
    Groups::table(),
    Usergroups::FD_CL_GROUP_ID,
    Groups::FD_CL_ID,
    ($l = SQLGrammar::GetCondString($driver, [
        Groups::FD_CL_CONTROLLER=>$ctrl::keyName()
    ])) ? ' WHERE '.$l : ''
);

igk_wln_e($query);
