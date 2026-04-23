<?php
use com\igkdev\app\L81\Models\DemoBasics;
use com\igkdev\app\L81\Models\RReports;
use com\igkdev\app\L81\ModelUtilities\L81DbUtility;
use IGK\Helper\JSon;
use IGK\System\Database\QueryBuilder;

L81Controller::ctrl()->register_autoload();
$member_id = 1;
$ctrl = L81Controller::ctrl();
igk_is_debug(1);
$g = DemoBasics::with(DemoBasics::table(), 'links')
->execute();
igk_wln_e($g, JSon::Encode($g));
$rp = RReports::Get(RReports::FD_RPT_ID, 27);
$cp = $rp->reportLastUpdate();
if ($cp){
    igk_wln('raw count: ', $cp->getRowCount());
    igk_wln('init: ', $cp->to_json());
}
igk_wln_e($cp);