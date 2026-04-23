<?php
// @author: C.A.D. BONDJE DOUE
// @filename: check-forem-job.php
// @date: 20251210 18:41:00
// @desc: 
// @command: balafon --run .test/forem-job-dashboard/check-forem-job.php
use com\igkdev\projects\ForemJobDashboard\EnumAuthorizations;
use com\igkdev\projects\ForemJobDashboard\Models\JobForemJobs; 

$ctrl = ForemJobDashboardController::ctrl(true);
$c = $ctrl->isUserAllowedTo(EnumAuthorizations::update_forem_reference);
igk_wln_e("check ..... ", $ctrl, $c);
$r = JobForemJobs::checkExists(igk_getv($params, 0, ''));
igk_assert_die($r === true, 'forem job reference not does\'t exists');