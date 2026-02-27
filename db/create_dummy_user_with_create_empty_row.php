<?php
// @command: balafon --run .test/db/create_dummy_user_with_create_empty_row.php
$p_version = implode('.', array_slice(explode('.', PHP_VERSION), 0,2));
$l = version_compare($p_version, "7.4", ">");
echo "------------------------------", PHP_EOL;
echo $p_version;
echo PHP_VERSION , '[ ' .$l.' ]', PHP_EOL;
exit;
use IGK\Models\Users;
$data = ['clLogin'=>'dummy', 'clPwd'=>'admin'];
$l = Users::createEmptyRow(true, true);
// if strict raise an error
// $l->papap = 322;
$l->loadFromArray($data);
function phone_def($clLogin=null, $clPwd=null){
    $l = Users::createEmptyRow(true, true)->loadFromArray(get_defined_vars());
   // Users::insert($k);
}
call_user_func_array('phpne_def', ['clLogin'=> 'Callisto']);
$l = version_compare(PHP_VERSION, "7.4", ">");
// compare_version 7.4 + "support parameter args"; 
igk_wln_e( $l);