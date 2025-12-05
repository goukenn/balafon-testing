<?php
 // @command: balafon --run .test/db/get_prefererred_phone.php
$user = igk_get_user_bylogin('cbondje@igkdev.com');
$g = $user->getPhoneBookEntryByType(); 
igk_wln_e('Phone booke entry type ', $g);