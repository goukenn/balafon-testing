<?php
// @command: balafon --run .test/db/jobresearch/load-claude-data.php
use com\igkdev\projects\ForemJobDashboard\Models\JobEnterprises;
use com\igkdev\projects\ForemJobDashboard\Models\Jobs;
use com\igkdev\projects\ForemJobDashboard\ModelUtilities\MainTaskModelUtility;
use IGK\System\Console\Logger;

$ctrl = ForemJobDashboardController::ctrl(true);
/**
* auto generate doc.
* @var MainTaskModelUtility
*/
$m_u = $ctrl->modelUtility('MainTask');
$data = igk_conf_get(
    json_decode(file_get_contents('/Users/charlesbondjedoue/Desktop/JobResponse/offres_emploi_complete_173.json')),
    'alertes_emploi_linkedin/offres'
);
Jobs::Delete([Jobs::FD_TITLE=>null]);
Jobs::Delete([Jobs::FD_TITLE=>'']);
Jobs::Delete([Jobs::FD_ENTERPRISE_ID=>null]);
Jobs::Delete([Jobs::FD_ENTERPRISE_ID=>'']);
Jobs::Delete([Jobs::FD_ENTERPRISE_ID=>'145']);
if ($user = $ctrl::login($user)) {
    if ($user = $ctrl->getUserProfile()->user()) { 
        foreach ($data as $row) {
            if (strstr( $row->titre, 'distance')){
                continue;
            }
            $job = $m_u->addJob(
                $row->titre,
                $user,
                null,
                $row->description
            );
            $c = trim($row->entreprise ?? '');
            $e = JobEnterprises::GetCache(JobEnterprises::FD_NAME, $c, true );
            if ($e)
            $job->enterprise_id = $e->id;
            $job->Create_At =igk_date_display_date($row->date, 'Y-m-d H:i:s');
            $job->save();
        }
    }
}
Logger::success('done');