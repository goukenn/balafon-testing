<?php
// @command: balafon --run .test/db/get-writing.php
use IGK\Models\Users;

$cl = Users::model()->getPrimaryKey();

$l = Users::GetCache('clguid', '{75B203A4-3555-8261-31F2-69055A1A8D3F}');
$m = Users::GetCache('clStatus', '1');
// $ms = Users::GetCache('login', 'cbondje@igkdev.com');

igk_wln_e($l->to_json());

