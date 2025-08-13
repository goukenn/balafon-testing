<?php
// @command: balafon --run .test/db/jobresearch/db-run.php
use com\igkdev\projects\ForemJobDashboard\Models\Jobs;
ForemJobDashboardController::ctrl(true);
echo Jobs::table();
// update all title to DevOps Engineer
$r = Jobs::update([Jobs::FD_TITLE=>'DevOps Engineer'], );
igk_exit();