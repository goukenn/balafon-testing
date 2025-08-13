<?php
// @command: balafon --run .test/newtest/php/extract_interface_definition.php
use com\igkdev\app\L81\Database\Macros\RReportsMacros;
use com\igkdev\app\L81\Models\RMembers;
use com\igkdev\app\L81\Models\RReports;
use IGK\Helper\Database;
use IGK\Helper\PhpHelper;
use IGK\Helper\SysUtils;
use IGK\Models\ModelBase;
use IGK\System\IO\File\PHPScriptBuilder;
use IGK\System\IO\StringBuilder;
L81Controller::ctrl()->register_autoload(); 
$sb = Database::GetPhpDocMacrosDefintionToInject(RReports::class);
$g = RReports::lastYearReport();
$member = RMembers::GetCache(RMembers::FD_CL_ID, 1) ?? igk_die("missing user");
// RReports::registerLastYearReport(1, 2022);
RReports::registerLastYearReport('Au Commencement, il y avait : ', $member, 2019);
// igk_wln_e($g->rpt_text);
$p_builder = new PHPScriptBuilder;
$p_builder->type('interface')
->uses(['\com\igkdev\app\L81\Models'])
->name('I')
->phpdoc($sb.'');
igk_wln($p_builder->render()); 
echo "report db macros".PHP_EOL;
exit;