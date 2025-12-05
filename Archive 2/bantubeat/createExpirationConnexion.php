<?php
use com\igkdev\bantubeat\Database\Macros\UsersMacros;
use com\igkdev\bantubeat\Models\Connexions;
igk_environment()->querydebug = 1;
$ctrl = bantubeatController::ctrl();
$ctrl::register_autoload();
$user = igk_get_user_bylogin('bondje.doue@igkdev.com');
$token = UsersMacros::RegisterToken($user);
$g = UsersMacros::Logout($user, $ctrl);
igk_wln_e("done", $g, $token);
$model = $ctrl->model(Connexions::class); // 'Connexions');
$model->update([
    "cnx_Expire_At" => date("Y-m-").str_pad(date("d") -1, 2,"0", STR_PAD_LEFT)
]);
// $r = $ctrl->model('Connexions')->select_all(['<cnx_Expire_At'=>"NOW()"]); 
$r = $model->clearExpiredToken();