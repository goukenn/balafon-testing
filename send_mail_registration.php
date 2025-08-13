<?php
use com\igkdev\bantubeat\Helper\MailService;
use IGK\System\DataArgs;
$ctrl = bantubeatController::ctrl();
$ctrl->register_autoload();
$user = [
    "clLogin"=>"bondje.doue@gmail.com",
    "clGuid"=>igk_create_guid()
];
$g = MailService::SendRegistrationMail(bantubeatController::ctrl(), new DataArgs( $user ));
igk_wln_e("check ", $g);