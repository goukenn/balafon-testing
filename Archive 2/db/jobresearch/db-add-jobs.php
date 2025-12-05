<?php
// just add job 
// @command: balafon --run .test/db/jobresearch/db-add-jobs.php
use com\igkdev\projects\ForemJobDashboard\ModelUtilities\MainTaskModelUtility;
use IGK\System\Console\Logger;
$ctrl = ForemJobDashboardController::ctrl(true);
$login = igk_getv($params, 0);
$title = igk_getv($params, 1) ?? igk_die('missing title');
$date = igk_getv($params, 2) ?? 'now';
$user = $ctrl::SignInUser($login);
if (($main = $ctrl::modelUtility("MainTask")) instanceof MainTaskModelUtility) {
    $cuser = $main->registerUser($user); 
 if ($job = $main->addJob($title, $cuser, intval(strtotime($date)))){
    Logger::print($job->to_json());
 }
}
Logger::success("done");