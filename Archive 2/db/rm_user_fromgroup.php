<?php
// remove user from group 
$login =  $params[0];
$group = $params[1];
$lg = igk_get_user_bylogin($login);
echo '?'.$lg->removeFromGroup($group);
exit;