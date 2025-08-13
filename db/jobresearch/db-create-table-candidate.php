<?php
// @command: balafon --run .test/db/jobresearch/db-create-table-candidate.php
use com\igkdev\projects\ForemJobDashboard\Models\Jobs;
use IGK\System\Caches\DBCachesModelInitializer;
use IGK\System\Console\Logger;
$ctrl = ForemJobDashboardController::ctrl(true);
$model =  Jobs::model();
$cls = $model->getTableColumnInfo();
$grammar = $model->getDataAdapter()->getGrammar();
$query = $grammar->createTableQuery("sjg", $cls);
$dbinit = new DBCachesModelInitializer();
// get class source to build
$src = "";
$src = $dbinit->getModelDefaultSourceDeclaration("Sample", $model->table(), $cls, "dummy form");
Logger::print($src);
Logger::success("done");
igk_exit();