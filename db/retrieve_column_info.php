<?php
use IGK\Controllers\SysDbController;
$ad = SysDbController::ctrl()->getDataAdapter();
if ($ad->connect()){
    $info = $ad->getGrammar()->retrieveStoredColumnInfo('tbigk_users', 'clId');
    igk_wln($info);
    $ad->close();
}
igk_wln_e('done');
igk_exit();