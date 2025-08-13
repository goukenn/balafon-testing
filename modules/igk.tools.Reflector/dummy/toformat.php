<?php
// @author: C.A.D. BONDJE DOUE
// @filename: toformat.php
// @date: 20250711 15:59:53
// @desc: presentation de start harmonize
 
interface IActions{
    function b();
    function a();
}

function a(){
    echo 'rover';
}

function b(){
    echo @'mercedes';
}


$i = 45 +   10 . 7   + b();
foreach range(1,5) as $k:
    echo $k
endforeach
 
exit;