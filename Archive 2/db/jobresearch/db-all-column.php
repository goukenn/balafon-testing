<?php
// @command: balafon --run .test/db/jobresearch/db-all-column.php --querydebug
use com\igkdev\projects\ForemJobDashboard\Models\JobForemJobs;
use com\igkdev\projects\ForemJobDashboard\Models\Jobs;
$ctrl = ForemJobDashboardController::ctrl(true);
// counting user - number_of_foremjobs
$T2 = JobForemJobs::class;
$T1 = Jobs::class;
$l = $T1::prepare()
->withTotalCount(true)
->columns([$T1::column($T1::FD_USER_ID)])
->groupBy([$T1::column($T1::FD_USER_ID)])
->join($T2::joinTableColumnOn($T2::FD_JOB_ID, $T1::column($T1::FD_ID)))
->execute();
echo "echo ".$l;
igk_exit();