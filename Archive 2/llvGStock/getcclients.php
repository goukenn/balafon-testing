<?php
use com\igkdev\app\llvGStock\Actions\ProductsAction;
use com\igkdev\app\llvGStock\Models\Operators;
use com\igkdev\app\llvGStock\Models\Resellers;
use com\igkdev\app\llvGStock\Models\Users;
use IGK\Database\DbExpression;
use function PHPUnit\Framework\callback;
// add fake user
// Users::factory(10)->create();
// $ac = new ProductsAction;
// $ac->setController($ctrl);
// $ac->vue();
$g = $ctrl->getResellerProductsForSale(null, '%z%');
igk_wln_e($g, $g->getRowCount());
$r = $ctrl->getClients(null,[
    IGKQueryResult::CALLBACK_OPTS=>function($r){
        // igk_wln("fetch callback");
        return $r;
    },
    "Limit"=>[0,10]
]);
//->join_left(Operators::table(), Operators::column(Operators::FD_OP_USER_ID)." = ".Users::column(Users::FD_USER_GUID))
// ->distinct(true)
// ->join_left(Resellers::table(), Resellers::column(Resellers::FD_RS_USER_GUID)." = ".Users::column(Users::FD_USER_GUID))
// ->join_left(Operators::table(), Operators::column(Operators::FD_OP_USER_ID)." = ".Users::column(Users::FD_USER_GUID))
// ->columns(["userId", "userGuid", Resellers::FD_RS_USER_GUID])
// ->where([
//     // "!".Operators::column(Operators::FD_OP_USER_ID)=>Users::column(Users::FD_USER_GUID),
//     // DbExpression::Create(
//     //     Resellers::column(Resellers::FD_RS_USER_GUID). " != ".
//     //     Users::column(Users::FD_USER_GUID)
//     // ),
//     // DbExpression::Create(
//     //     Operators::column(Operators::FD_OP_USER_ID). " != ".
//     //     Users::column(Users::FD_USER_GUID)
//     // ),
//     DbExpression::Create(
//         Users::column(Users::FD_USER_GUID). " NOT IN (". 
//             rtrim(Operators::prepare()
//                 ->columns([Operators::FD_OP_USER_ID])
//                 ->get_query(), ' ;')
//         .")"       
//     ),
//     DbExpression::Create(
//         Users::column(Users::FD_USER_GUID)." NOT IN (". 
//         rtrim(Resellers::prepare()
//             ->columns([Resellers::FD_RS_USER_GUID])
//             ->get_query(), ' ;')
//     .")"            
//     ),
// ])
// ->execute();
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