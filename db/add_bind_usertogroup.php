<?php
/**
 * 
 */
$login =  $params[0];
$ctrl = $params[1];
$group = $params[2];
$ctrl = igk_getctrl($ctrl, true);
$lg = igk_get_user_bylogin($login);
echo "bind to group ". $lg->bindToGroup($ctrl, $group);
echo PHP_EOL;