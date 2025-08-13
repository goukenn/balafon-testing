<?php
// @author: C.A.D. BONDJE DOUE
// @filename: update_display.php
// @date: 20250322 15:57:56
// @desc: use to update display fields
// @command: balafon --run .test/db/phpmyadmin/update_display.php
use IGK\Database\DbSchemas;
use IGK\Helper\SysUtils;
use IGK\System\Console\Logger;
use IGK\System\Database\MySQL\DataAdapter;
$c = SysUtils::GetControllerByName('%sys%');
$def = $c->getDataTableDefinition();
$displays = [];
$fc_call = function ($tables, &$display) {
    foreach ($tables as $k => $cl) {
        if ($d = igk_getv($cl, 'display')) {
            $display[$k] = $d;
        }
    }
};
$fc_call($def->tables, $displays);
foreach (igk_sys_project_controllers() as $p) {
    if ($dp = $p->getDataTableDefinition()) {
        $fc_call($dp->tables, $displays);
    }
}
ksort($displays);
$ad = igk_get_data_adapter('MYSQL');
$ad->connect();
$ad->selectdb('phpmyadmin');
$db = 'igkdev.dev';
$grammar = $ad->getGrammar();
foreach ($displays as $k => $v) {
    $tw = explode(',', $v);
    while (count($tw) > 0) {
        $q = array_shift($tw);
        $query = $grammar->createInsertQuery('pma__table_info', [
            'db_name' => $db,
            'table_name' => $k,
            'display_field' => $q
        ]);
        Logger::print('data: '. $query);
        try{
        $ad->sendQuery($query);
        }
        catch(\Exception $ex){
            Logger::danger('query failed => '.$ex->getMessage());
        }
    }
}
$ad->close();
igk_wln_e($displays);
igk_wln_e("sample .... ");