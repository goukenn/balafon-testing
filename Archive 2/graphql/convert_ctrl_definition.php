<?php
// balafon --run .test/graphql/convert_ctrl_definition.php
use IGK\Controllers\BaseController;
use IGK\Controllers\SysDbController;
use IGK\Database\DbColumnInfo;
use igk\io\GraphQl\Schemas\GraphQLDefinitionBuilder;
use igk\io\GraphQl\Schemas\GraphQlType;
use igk\io\GraphQl\Schemas\GraphQlFieldInfo;
use igk\io\GraphQl\System\Database\Helpers\GraphQlDbHelper;
use IGK\System\Console\Logger;
use IGKSysUtil as sysutil;
require_once IGK_LIB_DIR.'/Lib/functions-helpers/db.php';
$ctrl = SysDbController::ctrl(); 
echo GraphQlDbHelper::GenSQLDefinition($ctrl);
exit;