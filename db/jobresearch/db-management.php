<?php
// @author: C.A.D. BONDJE DOUE
// @filename: db-management.php
// @date: 20240910 19:33:47
// @desc: initialize command
// @command: balafon --run .test/db/jobresearch/db-management.php table,...
// + | --------------------------------------------------------------------
// + | command - init tables in schemas 
// + |
use IGK\Controllers\SysDbController;
// + | print_r(array_keys(get_defined_vars()));
// + | exit;
$action = igk_getv($params, 0);
$shift = 1;
if (!isset($ctrl)){
    $ctrl = igk_getctrl(igk_getv($params, 1), false);
    if ($ctrl){         
        $shift++;
    } else{
        $ctrl = SysDbController::ctrl(); 
    }
}
if ($ctrl === SysDbController::ctrl()){
    if (function_exists('readline')){
        $y = readline("core database selected. Do you want to continue? (y|n) ");
        if ( $y != 'y' ){
            igk_exit(-1);
        }
    } else{
        throw new Exception("missing readline for core setting");
    }
}
// direct test
// $action ='table';
// $ctrl = ForemJobDashboardController::ctrl();
// $shift = 1;
$allowed = 'table|rows';
if (!in_array($action, explode('|', $allowed))) {
    throw new Exception("not allowed action");
}
if (!$ctrl){
    throw new Exception('missing controller');
} 
$fc = 'igk_db_command_' . $action;
call_user_func_array( $fc, array_merge([$ctrl], array_slice($params, $shift)));