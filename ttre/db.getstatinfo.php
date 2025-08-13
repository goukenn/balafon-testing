<?php
use com\igkdev\projects\Ttre\Models\RecycleProducts;
$ctrl  = TtreController::ctrl();
$ctrl->register_autoload();
$g = RecycleProducts::GetStatsInfo();
if ($g !== false){
    print_r($g);
}
igk_wln_e($g);