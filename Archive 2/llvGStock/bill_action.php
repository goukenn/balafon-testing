<?php
// + | --------------------------------------------------------------------
// + | Requirement : 
// + |  -only add product with the same reseller
// + | 
use com\igkdev\app\llvGStock\BillStatusConstants;
use com\igkdev\app\llvGStock\Database\Seeds\DataBaseSeeder;
use com\igkdev\app\llvGStock\Models\Bills;
use com\igkdev\app\llvGStock\Models\Products;
use com\igkdev\app\llvGStock\Models\ProductStocks;
use com\igkdev\app\llvGStock\Models\BillDetails;
use com\igkdev\app\llvGStock\Models\BillStatusTypes; 
use com\igkdev\app\llvGStock\Models\Resellers;
use com\igkdev\app\llvGStock\PaymentConstants; 
use IGK\System\Console\Logger;
use IGK\System\Number;
/**
 * 
 * @package 
 */
class BillInfo{
    /**
     * 
     * @var mixed
     */
    var $user;
    /**
     * 
     * @var ?Resellers
     */
    var $reseller;
    /**
     * 
     * @var array<Products>
     */
    var $products = [];
    public function addProduct(Products $product, float $qte=0){
        if (is_null($product) || ($qte<=0)){
            return false;
        }
        if (is_null($this->reseller)){
            $this->reseller = Resellers::GetCache(Resellers::FD_RS_USER_GUID, $product->prodResellerGuid) ?? igk_die("reseller not found");
        }
        if ($this->reseller->rsUserGuid != $product->prodResellerGuid){
            igk_ilog("not the same reseller");
            return false;
        }  
        $stock = ProductStocks::Get(ProductStocks::FD_PDS_PRODUCT_ID,$product->prodId);
        if ($stock->pdsQte>=$qte){
            $stock->pdsReserved += $qte; 
            $stock->pdsQte -= $qte; 
            $stock->save();
        }
        if (!isset($this->products[$product->prodId])){
            $this->products[$product->prodId] = (object)["qte"=>0, "product"=>$product];
        }
        $this->products[$product->prodId]->qte += $qte; 
    } 
    public function store(){
        $reseller = $this->reseller;
        $user = $this->user;
        Products::beginTransaction();
        $T = 0;
        $items = [];
        foreach($this->products as $p){
            $product = $p->product;
            // + | DEPEND ON unit type price will change             
            $T += ($product->prodUnitPrice * $product->prodVat * $p->qte);
            $rs = BillDetails::createEmptyRow();
            $rs->bllDetail_productId = $product->prodId;
            $rs->bllDetail_quantity = $p->qte;
            $rs->bllDetail_unitPrice = $product->prodUnitPrice;
            $rs->bllDetail_vat = $product->prodVat;
            $items[] = $rs;
        }
        $this->reseller->rsBillCounter++;
        $indentifier =  $this->reseller->rsBillPrefix."-".Number::ToBase($this->reseller->rsBillCounter, 36, 3);
        $ref = Bills::createIfNotExists(
            [
                Bills::FD_BLL_IDENTIFIER => $indentifier,
                Bills::FD_BLL_USER_ID => $user->userGuid,
                Bills::FD_BLL_RESELLER_ID => $reseller->rsUserGuid,
                Bills::FD_BLL_TOTAL_AMOUNT => $T,
                Bills::FD_BLL_PRINTABLE =>1 ,
                Bills::FD_BLL_PAYMENT_ID => PaymentConstants::DbCacheValue(PaymentConstants::CASH) ,
                Bills::FD_BLL_BALANCE_AMOUNT =>0,                
                Bills::FD_BLL_STATUS_ID => BillStatusConstants::DbCacheValue(BillStatusConstants::Unpaid),
            ]
        );
        if ($ref){ 
            $b = true;           
            foreach($items as $p){
                $p->bllDetail_bllId = $ref->bllId;
                $b = $b && BillDetails::create($p);
            }
            if ($b && $this->reseller->save()){
                Products::commit();
                return true;
            } 
        }
        Products::rollback();
        return false;
    }
}
$id = BillStatusTypes::GetCache(BillStatusTypes::FD_BLL_STATUS_ID, BillStatusConstants::Unpaid);
$reseller = $ctrl->getReseller();
// _inProducts = Products::factory(50, [$reseller])->create();
if (empty($_inProducts = Products::GetResellerProduct($reseller))){
    $_inProducts = Products::factory(50, [$reseller])->create();
    DataBaseSeeder::SeedProductPrice($_inProducts); 
    DataBaseSeeder::SeedProductStock($_inProducts);
} else {
    // $_inProducts = Products::factory(50, [$reseller])->create();
    // DataBaseSeeder::SeedProductPrice($_inProducts); 
    // DataBaseSeeder::SeedProductStock($_inProducts);
}
$info = new BillInfo;
$product = $ctrl->getResellerProductsForSale($reseller);
$count = $product->getRowCount();
$info->user = $ctrl->getUser()->user() ?? igk_die("can't get users");
if ($count){
// add 10 product to bill info
$tab = $product->to_array();
$v = array_rand($tab, rand(1,20)) ;
foreach($v as $k){
    if (is_null($tab[$k]->prodId)){
        igk_wln_e("product id not found", $tab[$k]);
    }
    $p = Products::GetCache('prodId', $tab[$k]->prodId);
    $info->addProduct($p, rand(1, 10));
}
$info->store();
} else{
    Logger::danger("failed....");
}
Logger::print("done");
igk_exit();