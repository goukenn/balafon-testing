<?php
// @command: balafon --run .test/db/jobresearch/list_enterprises.php
// @author: C.A.D. BONDJE DOUE
// @filename: list_enterprises.php
// @date: 20250804 13:08:11
// @desc: list enterprises

use com\igkdev\projects\ForemJobDashboard\Models\JobEnterprises;

ForemJobDashboardController::ctrl(true);
$kf = (object)['name'=>JobEnterprises::FD_NAME];
// remove unkown 
// JobEnterprises::delete([$kf->name=>'UNKNOW']);

$fd = JobEnterprises::select_all(null, [
    'OrderBy'=>[JobEnterprises::FD_NAME().'|asc']
]);

echo implode(',', array_map(function($a)use($kf){
    return strtoupper($a->{$kf->name});
}, $fd));

echo PHP_EOL;

exit;
