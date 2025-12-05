<?php
// @author: C.A.D. BONDJE DOUE
// @filename: search.php
// @date: 20250509 10:13:50
// @desc: 
// @command: balafon --run .test/vcard/search.php
use IGK\Database\PhoneBookUtility;
use IGK\Helper\JSon; 
($search = igk_getv($params, 0)) ?? igk_die('missing search');
$l = PhoneBookUtility::Search($search);
igk_wln_e(JSon::Encode($l));