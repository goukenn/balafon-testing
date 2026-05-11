<?php
use IGK\Database\DbColumnInfo;
use IGK\Models\ModelBase;
use IGK\Models\Users;
use IGK\System\Console\Logger;
/**
* auto generate doc.
* @param ModelBase $model
* @param string $column
* @param string $new_name
* @return mixed
*/
function db_rename_table_column(ModelBase $model, string $column, string $new_name ){
    $ad = $model->getDataAdapter();
    $info =  $model->getTableInfo();
    $table = $model->getTable();
    $tinfo = $info->columnInfo[$column];
    $tinfo->clName = $new_name;
    $prim = $model->getPrimaryKey();
    $tprim = null;
    if ($prim != $column){
        $tprim = $info->columnInfo[$prim];
    } else {
        $tprim = new DbColumnInfo([
            'clName'=>'primKey',
            'clType'=>'Int',
            'clAutoIncrement'=>1,
            'clIsPrimaryKey'=>1
        ]);
    }
$query = null;
   $ad->sendQuery(igk_str_format('ALTER TABLE `{0}` DROP COLUMN `{1}`', $table, $new_name));
    if ($ad->exist_column($table, $column)) {
        $ad->sendQuery('Drop table IF EXISTS `memo`;');
        $ad->createTable('memo', [
            $tprim->clName=>$tprim,
            $new_name=>$tinfo
        ], null, 'Memory Table', ['Engine'=>'Memory']);
        $query = igk_str_format('INSERT INTO memo (`{0}`, `{1}`) SELECT {0}, {3} FROM {2};', $tprim->clName, $new_name, $table, $column);
        Logger::info($query);         
        $ad->sendQuery($query);
        $query = $ad->grammar->add_column($table, $tinfo, null);
        Logger::print('Add Column = '.$query);
        $ad->sendQuery($query);
        $m = $ad->selectAll('memo');
        if ($m)
        foreach($m->getRows() as $row){
            Logger::warn($row->to_json());
            $ad->update($table, [
                $new_name=>$row->$new_name
            ], [
                $tprim->clName =>$row->{$tprim->clName}
            ]);
        }
       $ad->sendQuery('Drop table `memo`;');
    } 
}
igk_environment()->querydebug = 1;
igk_get_user_bylogin('cbondje@igkdev.com')->activate();
Logger::success('done');
igk_exit();