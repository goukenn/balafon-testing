<?php
function LargestFour($tab){ $c = count($tab); $sum = 0; if ($c>0){ rsort($tab); $tab = array_slice($tab,0, 4); 
    while(count($tab)>0){$sum+= array_shift($tab);}} 
return $sum; }
igk_wln("for : [] ", LargestFour([]));
igk_wln("for : [4, 5, -2, 3, 1, 2, 6, 6] = 21 ?", LargestFour([4, 5, -2, 3, 1, 2, 6, 6]));
igk_wln("for : [4, 5, -2] = 7 ? ", LargestFour([4, 5, -2]));
igk_exit();