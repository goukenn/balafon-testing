<?php
// @command: balafon --run .test/newtest/php/extract_interface_definition.php 
use com\igkdev\app\L81\Models\RMembers;
use com\igkdev\app\L81\Models\RReports;
use IGK\Helper\Database; 
use IGK\System\IO\File\PHPScriptBuilder; 

L81Controller::ctrl()->register_autoload(); 
$sb = Database::GetPhpDocMacrosDefintionToInject(RReports::class);
$g = RReports::lastYearReport();
$member = RMembers::GetCache(RMembers::FD_CL_ID, 1) ?? igk_die("missing user");
RReports::registerLastYearReport('Au Commencement, il y avait : ', $member, 2019);
$p_builder = new PHPScriptBuilder;
$p_builder->type('interface')
->uses(['\com\igkdev\app\L81\Models'])
->name('I')
->phpdoc($sb.'');
igk_wln($p_builder->render()); 
echo "report db macros".PHP_EOL;
igk_exit();