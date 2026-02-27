<?php
// @author: C.A.D. BONDJE DOUE
// @filename: db-handle-display.php
// @date: 20251226 13:57:10
// @desc: 
// @command: balafon --run .test/string/db-handle-display.php --user:login
use IGK\Models\PhoneBooks;
use IGK\Models\Users;
use IGK\System\Console\Logger;
$model = $user->model();
// $r = PhoneBooks::first(null, PhoneBooks::FD_ID);
$r = PhoneBooks::lastByColumn(null, PhoneBooks::FD_ID);
//$r = PhoneBooks::GetCache(PhoneBooks::FD_ID, 30);
igk_wln_e( $r->display());
//$model::$display = '^clLogin';
// echo $user->display();
Logger::success("done");