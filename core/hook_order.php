<?php
// @command: balafon --run .test/core/hook_order.php

igk_reg_hook('mon_hook', function($event) {
    echo "Callback 1 (priorité 5)\n";
}, 5);
igk_reg_hook('mon_hook', function($event) {
    echo "Callback 2 (priorité 10)\n";
}, 10);
igk_reg_hook('mon_hook', function($event) {
    echo "Callback 3 (priorité 3)\n";
}, 3);
igk_hook('mon_hook');