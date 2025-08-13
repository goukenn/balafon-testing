<?php
// balafon --run .test/llvGStock/addBill.php -db_server:0.0.0.0
use com\igkdev\app\llvGStock\BillStatusConstants;
use com\igkdev\app\llvGStock\ConfigsParameterConstants;
use com\igkdev\app\llvGStock\Database\Macros\BillMacros;
use com\igkdev\app\llvGStock\Groups;
use com\igkdev\app\llvGStock\Models\Bills;
use com\igkdev\app\llvGStock\Models\BillStatusTypes;
use com\igkdev\app\llvGStock\Models\Products;
use com\igkdev\app\llvGStock\Models\Resellers;
use com\igkdev\app\llvGStock\Models\Users; 
$ctrl = LlvGStockController::ctrl();
$ctrl->register_autoload();
igk_debug(1);
$row = Products::createEmptyRow();
igk_wln_e($row);
$login = $ctrl->getConfig(ConfigsParameterConstants::mainResellerLogin);
if (!$login){
    igk_die("mainResellerLoging not configured");
}
$products = [];
$user = $ctrl->getUser();
$reseller = $ctrl->getReseller();
$g = Products::select_all();
$p = $g[3];
$int = $p->getStock();
$p->addStock(500);
igk_wln_e("stock : ", $int, $p);
if (is_null($reseller )){
    $ctrl->registerResellerByLogin(
        "IGKDEV"
    );
    if ($regUser = igk_get_user_bylogin($login)){
        $regUser = Users::AddIfNotExists($regUser->clGuid,null, null, Groups::Reseller );
        $reseller = Resellers::create([
            Resellers::FD_RS_USER_GUID=>$regUser->userGuid,
            Resellers::FD_RS_NAME => "IGKDEV",
            Resellers::FD_RS_SITE => "//igkdev.com",
            Resellers::FD_RS_VAT  => "8025477894",
            Resellers::FD_RS_ADDR_COUNTRY => "BE",
            Resellers::FD_RS_ADDR_CITY => "MONS",
            Resellers::FD_RS_ADDR_STREET => "Place de la grande pêcheries",
            Resellers::FD_RS_ADDR_NUMBER => "7",
            Resellers::FD_RS_ADDR_POSTAL_CODE => "7000",
            Resellers::FD_RS_ADDR_BOX => "G",
        ]);
    }
    if (is_null($reseller)){
        igk_die("reseller was null");
    }
}
$paid = BillStatusTypes::Get(BillStatusTypes::FD_BLL_STATUS_ID, BillStatusConstants::Paid);
$g = BillMacros::AddBill(
    Bills::model(),
    $user->user(),
    $reseller,
    $products,
    $paid
);
// $billinfo = Bills::AddBill(
//     $user->user(),
//     $reseller,
//     $products,
//     $paid
// );
igk_wln_e($billinfo);