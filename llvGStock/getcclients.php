<?php
use com\igkdev\app\llvGStock\Actions\ProductsAction;
use com\igkdev\app\llvGStock\Models\Operators;
use com\igkdev\app\llvGStock\Models\Resellers;
use com\igkdev\app\llvGStock\Models\Users;
use IGK\Database\DbExpression;
use function PHPUnit\Framework\callback;

$g = $ctrl->getResellerProductsForSale(null, '%z%');
igk_wln_e($g, $g->getRowCount());
$r = $ctrl->getClients(null,[
    IGKQueryResult::CALLBACK_OPTS=>function($r){
        return $r;
    },
    "Limit"=>[0,10]
]);
$userid = '{82FDEF90-276F-B05E-B6E9-BCD2924993F2}';
$is_reseller = null;
$is_operator = null;
if ($r && ($r->getRowCount()>0)){
$user = $r->getRowAtIndex(0);
$userid = $user->userGuid;
$is_reseller = Resellers::select_row(["rsUserGuid"=>$userid ]);
$is_operator = Operators::select_row([Operators::FD_OP_USER_ID=>$userid ]);
igk_wln_e($r, $r->getRowCount(), $user->to_json(), "reseller: ", $is_reseller, "operator : ", $is_operator );
} 
igk_wln_e("failed .",  "reseller: ", $is_reseller, "operator : ", $is_operator );