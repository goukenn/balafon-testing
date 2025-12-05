<?php
use IGK\Helper\Authorization;
use IGK\Helper\JSon;
use IGK\System\Http\WebResponse;
$c = bantubeatController::ctrl();
$c::register_autoload();
$g = Authorization::GetGroups(bantubeatController::ctrl());
$rep = new \com\igkdev\bantubeat\System\Api\Response;
$rep->response = $g;
$rep->code = 200;
igk_wln_e(WebResponse::Create('json', $rep, 200)->output());
igk_wln_e(JSon::Encode($g));