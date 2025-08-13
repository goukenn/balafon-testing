<?php

// @command: balafon --run .test/db/jobresearch/load_ia_list.php

use com\igkdev\projects\ForemJobDashboard\Models\JobEnterprises;
use com\igkdev\projects\ForemJobDashboard\Models\JobForemJobs;
use com\igkdev\projects\ForemJobDashboard\Models\Jobs;
use com\igkdev\projects\ForemJobDashboard\Models\JobUsers;
use IGK\Helper\JSon;
use IGK\System\Console\Logger;
use IGK\System\IO\File\CsvFile;
use IGK\System\Text\RegexMatcherContainer;

ForemJobDashboardController::ctrl(true);

$l = __DIR__ . '/data/ia_list.csv';
$csv = new CsvFile();
$csv->separator = ';';
$g = $csv->parseData(file_get_contents($l));
$header = array_shift($g);

$unk = JobEnterprises::GetCache(JobEnterprises::FD_NAME, 'UNKNOWN');
$uid = ForemJobDashboardController::SignInUser();
$rid = JobUsers::GetCache(JobUsers::FD_GUID, $uid->clGuid);
$tc = Jobs::select_all([
    Jobs::FD_ENTERPRISE_ID => null,//$unk,
    //.Jobs::FD_USER_ID => $rid->id
]);
$desc = [];
$same = [];
foreach ($tc as $row) {
    $tab = rand(0, count($g) - 1);

    $row->title = $g[$tab][1];
    $row->enterprise_id = JobEnterprises::GetCache(JobEnterprises::FD_NAME, $g[$tab][0])->id;
    $row->description = 
    $row->summary = $g[$tab][2];
    if (isset($desc[$row->description])) {
        if (!isset($same[$row->description])) {
            $same[$row->description] = 0;
        }
        $same[$row->description]++;
    }
    //igk_wln($row->description);
    $desc[$row->description] = $row->description;
    $row->update();
}

igk_wln_e(array_values($same));

igk_wln_e(JSon::Encode($tc, null, JSON_PRETTY_PRINT));
