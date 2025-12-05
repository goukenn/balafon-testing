<?php
// balafon --run .test/db/drop_tables.php laravel_tbbm_%
use IGK\Controllers\SysDbController;
use IGK\System\Console\Logger;
$ctrl = SysDbController::ctrl();
$ctrl->register_autoload();
$pattern = igk_getv($params, 0);
if (!$pattern){
    igk_die("missing pattern");
}
// Drop Tables 
$ad = $ctrl->getDataAdapter();
$tab = $ad->sendQuery(sprintf('Show tables like \'%s\';', $pattern));
array_map(function($a)use($ad){
    $table = array_values($a->to_array())[0];  
    Logger::print("drop : ".$table);
    $ad->dropTable($table); 
}, $tab->to_array());
Logger::success("done");  