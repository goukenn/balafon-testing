<?php
use IGK\Helper\JSon;
use IGK\Models\Users;

igk_debug(true);
echo "delete ? ". Users::delete(['clLogin'=>"cbondje@igkdev.com"]); 
$ctrl = TtreController::ctrl();
$ctrl->register_autoload();
// + | register mail service 
IGKServices::Register(\MailService::class, $ctrl->resolveClass(\Services\MailService::class));
igk_server()->SERVER_NAME = 'local.com';
igk_server()->SERVER_PORT = '7300';
igk_server()->REQUEST_METHOD = 'POST';
igk_server()->HTTP_ACCEPT_ENCODING = "application/json";
if ($action = $ctrl->action(\Api\ApiAction::class)){
    igk_environment()->setFakerInputData(
    json_encode([
        'login'=>'cbondje@igkdev.com'
        ])
    ); 
    $action::Handle($ctrl, "default", ["register"]);
}
$u_model = $ctrl->model(\Users::class);
igk_wln_e('action : ', $action);
echo "delete ? ". Users::delete(['clLogin'=>"cbondje@igkdev.com"]);
$u = Users::Register(['clLogin'=>"cbondje@igkdev.com"]);
if ($u)
echo $u->to_json();