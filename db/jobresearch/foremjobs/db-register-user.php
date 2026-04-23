<?php
// @author: C.A.D. BONDJE DOUE
// @filename: db-register-user.php
// @date: 20250804 10:24:59
// @desc: 
use com\igkdev\projects\ForemJobDashboard\Models\JobForemJobs;
use com\igkdev\projects\ForemJobDashboard\Models\Jobs;
use com\igkdev\projects\ForemJobDashboard\ModelUtilities\MainTaskModelUtility;
use IGK\Models\Users;

$ctrl = ForemJobDashboardController::ctrl(true);
$tk = array_rand(range(0,44), 5);
if (($main = $ctrl->modelUtility("MainTask")) instanceof MainTaskModelUtility) {
    $p = Users::factory(3)->create();
    while (count($p)) {
        $pu = array_shift($p);
        $cu = $main->registerUser($pu);
        $jobs = Jobs::factory(50, null, ['jb_user' => $cu])->create();
        $rand_incides = array_rand($jobs, rand(3, 30)); 
        $u = JobForemJobs::factory(rand(4, 10), null, ['rand_indices' => $rand_incides, "jobs" => $jobs])->create();
    }
    igk_wln_e($p, $jobs);
}