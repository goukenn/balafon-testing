<?php
// @author: C.A.D. BONDJE DOUE
// @filename: use_match.php
// @date: 20260408 03:59:45
// @desc: demonstration of match
// @command: balafon --run .test/php8/use_match.php

$expression = null;
try{
echo match ($expression) {
     'o'=>'odd' ,
     'simple'=>'dex' ,
};
}
catch(\Error $ex){
    igk_wln('Error : ', get_class($ex));
}
igk_wln_e('done');