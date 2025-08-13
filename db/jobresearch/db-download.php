<?php
// 
// @create a download script ... 
// @command: balafon --run .test/db/jobresearch/db-download.php --querydebug
// @description : output/.zip to be expored   
// 
use IGK\Helper\IO;
use IGK\Resources\R;
use IGK\System\Console\Logger;
use function igk_resources_gets as __;
$ctrl = ForemJobDashboardController::ctrl(true);
$output = __DIR__.'/output/forem';
IO::rmdir($output, true);
// set language fr 
R::LoadCtrlLang($ctrl); 
$response = $ctrl->outputDownload([
    'output' => $output,
    'outzip' => null,
    'login'  => igk_getv($_ENV, 'IGK_DEFAULT_USER', 'cbondje@igkdev.com') ,
    'forem_presentation'=>true
]);
Logger::success("output: ".$output);
igk_exit();