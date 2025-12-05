<?php
// 
// @title: register forem job with reference 
// @command : balafon --run .test/db/jobresearch/db-register-job.php [ref] [for_id]
// @description: register forem job with reference 
// 
use com\igkdev\projects\ForemJobDashboard\Models\JobForemJobs;
use com\igkdev\projects\ForemJobDashboard\Models\Jobs;
use com\igkdev\projects\ForemJobDashboard\ModelUtilities\MainTaskModelUtility;
$ctrl = ForemJobDashboardController::ctrl(true);
if (($main = $ctrl->modelUtility("MainTask")) instanceof MainTaskModelUtility){
    $job = Jobs::select_row(igk_getv($params, 1, 0)) ?? igk_die("missing users");
    $p = $main->registerForemJob($job, igk_getv($params, 0,  "0015479"));
    if ($p && is_bool($p)){
        $p = JobForemJobs::select_row(JobForemJobs::last_id());
        $p->description = \Faker\Factory::create()->sentence(rand(0, 10));
        $p->update();
    }
    if ($main->isForemJobs($job)){
        $fref =  JobForemJobs::Get(JobForemJobs::FD_JOB_ID, $job->{Jobs::FD_ID});
        $tg["ForemReference"] = $fref->reference;
        igk_wln($tg, $fref->to_array());
    }
}
igk_wln_e("done");