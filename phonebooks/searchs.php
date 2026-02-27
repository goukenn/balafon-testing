<?php
// @author: C.A.D. BONDJE DOUE
// @filename: uniques.for.php
// @date: 20251221 14:09:07
// @desc: 
// @command: balafon --run .test/phonebooks/uniques.for.php
use IGK\Helper\JSon;
use IGK\Models\PhoneBooks; 
!isset($user) && igk_die("required user");
$search = igk_getv($params, 0);
$r = PhoneBooks::userSearchPhoneEntries($user, $search);
igk_wln_e(JSon::Encode($r->to_array()));