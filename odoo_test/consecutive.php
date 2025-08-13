<?php
// odoo test test consecutive
// @command: balafon --run .test/odoo_test/consecutive.php
function Consecutive(array $tab){ if (($c = count($tab))<2) return 0; $max=$min=$t=0;foreach($tab as $i){ 
    if (!$t){ $min=$max=$i; $t=1; continue;}
    $max=max($max, $i); $min=min($min,$i); } return (($max-$min)+1) -$c;}
igk_wln("Number of item to add in array of int in order to be Consecutive");
igk_wln( "[4,8,6,20] = 13 ? ", Consecutive([4,8,6,20]) );
igk_wln( "[4,8,6] = 2 ? ", Consecutive([4,8,6]) );
igk_wln( "[-14,-10] = 3 ? ", Consecutive([-14,-10]) );
igk_wln( "[-14,-10,10] = 22 ? ", Consecutive([-14,-10, 10]) );
exit;