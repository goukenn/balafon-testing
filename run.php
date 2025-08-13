<?php
// balafon --run --controller:ttreController /Volumes/Data/Dev/PHP/balafon_site_dev/.test/run.php
use com\igkdev\projects\Ttre\Database\Constants\ProductUnitTypeConstants;
use com\igkdev\projects\Ttre\Models\RecycleProviders;
$data = ProductUnitTypeConstants::GetCacheData(ProductUnitTypeConstants::Quantity);
igk_wln_e("data : ", $data);
igk_wln_e(RecycleProviders::select_row(
   3
)->getPhoneEntry()->to_json(null, JSON_PRETTY_PRINT));