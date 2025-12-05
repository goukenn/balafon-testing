<?php
// balafon --run --controller:bantubeatController ./.test/db/rename_column_migration.php users usr_
use IGK\System\Html\XML\XmlNode;
/**
 * @var \IGK\Controllers\BaseController $ctrl
 */
list($table,  $prefix) = $params;
$model =  $ctrl->model($table) ?? igk_die('model not found.');
$v_gtable = $model->getDefTable();
$cfinfo = $model->getTableColumnInfo();
$m = new XmlNode("Migrations");
$mig = null;
foreach($cfinfo as $k=>$cl){
    $name = $cl->clName;
    if (strpos($name, $prefix)!==0){
        $name = $prefix.$name;
        if (is_null($mig )){
            $mig = $m->add("Migration");
        }
        $g = $mig->add('renameColumn');
        $g['table'] = $v_gtable;
        $g['column'] = $cl->clName;
        $g['new_name'] = $name;
    }
}
$m->renderAJX();
exit;