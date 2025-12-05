<?php
use IGK\Database\DbColumnInfo;
use IGK\Models\ModelBase;
use IGK\Models\Users;
use IGK\System\Console\Logger;
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
    // debug 
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
        // $query = igk_str_format('UPDATE `{0}` JOIN `memo` ON `memo`.`{1}` = `{0}`.`{4}` SET `{0}`.`{3}`=`memo`.`{3}`;', 
        //     $table, $tprim->clName, $column, $new_name, $prim);   
        //     Logger::info($query)     ;
        // $ad->sendQuery($query);
       $ad->sendQuery('Drop table `memo`;');
    } 
}
// db_rename_table_column(Users::model(), 'clcreate_at', 'clcreate_atation');
igk_environment()->querydebug = 1;
igk_get_user_bylogin('cbondje@igkdev.com')->activate();
// Users::model()->getDataAdapter()->createTable('base_i', [
//     new DbColumnInfo([
//         'clName'=>'id',
//         'clType'=>'int',
//         'clAutoIncerment'=>true
//     ]),
//     new DbColumnInfo([
//         'clName'=>'clDate',
//         'clInsertFunction'=>'Now()',
//         'clType'=>'datetime',
//         'clDefault'=>'CURRENT_TIMESTAMP'
//     ]),
// ]);
// Users::model()->getDataAdapter()->sendQuery('Drop table base_i');
Logger::success('done');
igk_exit();