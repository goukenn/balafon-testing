<?php
// @author: C.A.D. BONDJE DOUE
// @filename: generate_prisma_file.php
// @date: 20231216 19:19:07
// @desc: 
// @command: balafon --run .test/prisma/generate_prisma_file.php
use IGK\Controllers\SysDbController;
use igk\js\Prisma\PrismaDefinitionBlock;
use IGK\System\IO\StringBuilder; 
use igk\js\Prisma\PrismaModelBuilder;
use igk\js\Prisma\Schema\Attributes\Relation;
use IGK\System\Console\Logger;
if (count($params)>0)
$ctrl = igk_getctrl($params[0]) ?? igk_die('missing controller');
$table_name = '';
$definition = '';
$columnInfo = [];
$fb = igk_require_module(\igk\social\facebook::class); 
$prisma = igk_require_module(\igk\js\Prisma::class);
// $r = new Relation();
// $r->name = "SampleData";
// $r->fields = ['x','targetX'];
// $r->references = ['referencX'];
// echo ''.$r .PHP_EOL;
// $id = new DefaultAttribute();
// echo ''.$id. PHP_EOL;
// exit;
// igk_debug(true);
// $rm = $ctrl::getCachedDataTableDefinition(); // 
$rm = $ctrl::getCachedDataTableDefinition();
if (empty($rm)){
    Logger::warn('failed to get data defintion from schema');
    igk_exit();
}
// $rm = SysDbController::getDataTableDefinition();
// $users_info = SysDbController::getDataTableDefinition("tbigk_users");
// class PrismaModelBuilder{
//     var $provider = 'mysql';
//     var $url = 'env(\"DATABASE_URL\")';
// }
$model = new PrismaModelBuilder;
echo $model->generatePrismaContent($rm); 
igk_exit();