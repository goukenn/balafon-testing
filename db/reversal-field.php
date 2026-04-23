<?php
// @command: balafon --run .test/db/reversal-field.php
use IGK\Controllers\SysDbController;
use IGK\Database\DbSchemas;
use IGK\System\Caches\DBCaches;
use IGK\System\Console\Colorize;
use IGK\System\Console\Logger;
use IGK\System\Database\DbConditionExpressionBuilder;
use IGK\System\Database\Helper\DbUtility;
use IGK\System\Database\Import\DbModelImporterMap;

$table = 'tbigk_users';
$colorizer = new Colorize;
$r = DBCaches::GetTableInfo('tnbk_sites');
$tr = DbUtility::PreparateConditionsListToAvoidDuplicate($r->columnInfo, [
    "sts_id"=>9,
    "sts_name"=>"xvideos",
    "sts_site"=>"https://bing.com",
    "sts_primary_cat_id"=>"11"
]);
$m = $r->model();
$import = new DbModelImporterMap($m);
$file = IGK_DEV_DIR. '/JsonDatas/sites.json';
if (!file_exists($file)){
    igk_die('missing json data');
}
$import->autoregister = true;
$import->addFieldListener('primaryCategory', function(& $tab, $key, $map){
    $v = $tab[$key];
    unset($tab[$key]);
    if (is_array($v)){
        $v = igk_getv($v, 0);
    }
    $tab['sts_primary_cat_id'] = $v;
});
$tab = json_decode(file_get_contents($file));
array_map($import, $tab->sites);
exit;
$g = DbUtility::GetReversalUniqueColumn($table, false);
if ($g) {
    Logger::success("reversal col : " . implode(",", array_map('trim', array_keys($g))));
    $r = DBCaches::GetTableInfo($table);
    $model = $r->model(); 
    $login = "cbondje@igkdev.com";
    $cond = new DbConditionExpressionBuilder(DbConditionExpressionBuilder::OP_OR);
    $cond->add("clGuid", $login);
    $cond->add("clLogin", $login);
    $result = $model::select_row([$cond]);
    Logger::SetColorizer($colorizer);
    Logger::print($result->to_json()); 
    Logger::SetColorizer(null); 
    echo $cond . "\n";  
}
Logger::success("done");