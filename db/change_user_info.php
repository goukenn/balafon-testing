<?php
// @command: balafon --run .test/db/change_user_info.php
use IGK\Models\Users;

$g = igk_get_user_bylogin('willymeli@yahoo.fr');
$g->clFirstName = 'Anatole';
$g->clLastName = 'MELI FOTSO';
$g->update();