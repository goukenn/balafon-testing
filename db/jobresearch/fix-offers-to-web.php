<?php
// @command: balafon --run .test/db/jobresearch/fix-offers-to-web.php
use com\igkdev\projects\ForemJobDashboard\Models\Jobs;
use com\igkdev\projects\ForemJobDashboard\Models\JobSourceTypes;
use com\igkdev\projects\ForemJobDashboard\ModelUtilities\MainTaskModelUtility;
use com\igkdev\projects\ForemJobDashboard\SourceTypes;
use IGK\System\Console\Logger;
$ctrl = ForemJobDashboardController::ctrl(true);
$callback = function($user, $ctrl)use($params){
    /**
    *  @var MainTaskModelUtility
    */
    $search = igk_getv($params, 0);
    $m_u = $ctrl->modelUtility('MainTask');    
    $list = $m_u->listJobs($user, $search ?? '2024');
    $id = JobSourceTypes::GetCache( JobSourceTypes::FD_NAME, SourceTypes::WEB)->id;
    $nid = JobSourceTypes::GetCache( JobSourceTypes::FD_NAME, SourceTypes::LINKEDIN)->id;
    foreach($list as $row){
        /**
         * @var $row Jobs
         */
        $update = false;
        if ($row->{Jobs::FD_FROM_ID} == $id){
            $update = true;
            $row->{Jobs::FD_FROM_ID} = $nid;
        }
        if ($row->{Jobs::FD_DESCRIPTION} == $row->{Jobs::FD_SUMMARY}  ){
            $row->{Jobs::FD_SUMMARY} = '';
            $update = true;
        }
        if ($update && rand(0,1)){
            Logger::info('update - '.$row->id);
            $row->save();
        }
    }
};
include __DIR__.'/.auth.pinc';