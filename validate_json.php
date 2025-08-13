<?php
use IGK\Helper\JSon;
$data = \IGK\System\Npm\JsonPackage::Load($package = igk_io_packagesdir()."/package.json");
if ($data){
    $data->mergeWith(__DIR__.'/dummy.json');
} else {
    igk_wln_e("validation error ", igk_environment()->last_error);
}
igk_io_w2file($package, $rep= JSon::Encode($data,(object)['ignore_empty'=>1],JSON_PRETTY_PRINT| JSON_UNESCAPED_SLASHES));
igk_wln_e( $rep); // JSon::Encode($data,(object)['ignore_empty'=>1],JSON_PRETTY_PRINT| JSON_UNESCAPED_SLASHES));