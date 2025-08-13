<?php
// @command: balafon --run .test/db/retreive_foreing_keys/foreign.php
use IGK\Controllers\SysDbController;
use IGK\System\Console\Logger;
$c = SysDbController::ctrl(true);
$ad = $c->getDataAdapter();
$g = $ad->reverse_foreing_keys("tbigk_users", "clGuid");
if ($ad->exist_column('tbigk_users', 'clGuid')){    
    $s = null;
    try{
        $s = $ad->sendQuery('ALTER TABLE tbigk_users DROP INDEX clGuid;', false);
    } catch(Exception $ex){
        Logger::danger("column is probably not an index");
        $s = null;
    }
    if ($s){
        Logger::success("ok - drop index");
    }
}
igk_wln_e("done");