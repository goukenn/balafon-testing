<?php

// @author: C.A.D. BONDJE DOUE
// @filename: add-enterprise.php
// @date: 20250804 11:35:42
// @desc: just add enterprises
// @command: balafon --run .test/db/jobresearch/add-enterprise.php
use com\igkdev\projects\ForemJobDashboard\Models\JobForemJobs;
use com\igkdev\projects\ForemJobDashboard\Models\JobEnterprises;
ForemJobDashboardController::ctrl(true);
$name = igk_getv($params, 0) ?? igk_die('missing enterprise name');
foreach(explode(',', $name) as $c){
    try{
        $l = JobEnterprises::Add(trim($c));
    }
    catch(\Exception $ex){
        igk_wln('data: '.$ex->getMessage());
    }
    finally{

    }
}

echo JobEnterprises::count();
exit;