<?php
use IGK\Models\Users;
$ctrl = bantubeatController::ctrl();
$ctrl->register_autoload();
$user = $ctrl->model(\Users::class)->select_row(1); 
$g = $user->updateSocial([
    "facebook"=>"https://facebook.com/igkdev",
    "twitter"=>"https://twitter.com/igkdev"
]);
igk_wln_e($g->to_json());