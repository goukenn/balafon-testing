<?php

$login =  $params[0];
$group = $params[1];
$lg = igk_get_user_bylogin($login);
echo '?'.$lg->removeFromGroup($group);
igk_exit();