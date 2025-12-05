<?php
// @author: C.A.D. BONDJE DOUE
// @filename: fjbd_counting_day.php
// @date: 20250804 15:10:42
// @desc: counting number of jobsearch / day 

// @command: balafon --run .test/db/jobresearch/fjbd_counting_day.php --querydebug
use com\igkdev\projects\ForemJobDashboard\Models\Jobs;
$c = ForemJobDashboardController::ctrl(true);
//counting searching per day 
$T1 = Jobs::class;
$r = $T1::prepare()
->columns(
    ['count(*)', Jobs::FD_CREATE_AT]
)
->groupBy([Jobs::FD_CREATE_AT])
->execute();
igk_wln_e($r->to_json(null, JSON_PRETTY_PRINT));