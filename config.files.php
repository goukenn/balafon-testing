<?php
// + | --------------------------------------------------------------------
// + | check for a controller configuration files
// + |
$rs = tbn_ctrl::ctrl()->configFile('profiles.php');
igk_wln_e($rs);