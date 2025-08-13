<?php
// @command: balafon --run .test/db/dropAllRelations.php
use IGK\Controllers\SysDbController;
$ctrl = SysDbController::ctrl(true);
$rs = $ctrl->getDataAdapter()->dropAllRelations();
echo $rs;
exit;