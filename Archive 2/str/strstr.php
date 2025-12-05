<?php
// /Volumes/Data/Dev/PHP/balafon_site_dev/.test/str/strstr.php
$s = '/Volumes/Data/wwwroot/core/Packages/Modules';
$s = 'wwwroot/core/Packages/Modules';
$a = '/Volumes/Data/wwwroot/core/Packages/Modules/igk/pay/paypal/Lib/Classes/Component/paypalpaymentCtrl.php';
echo strstr($s, $a), PHP_EOL;
echo '-----', PHP_EOL;
echo strstr($a, $s), PHP_EOL;
exit;