<?php
// @command: balafon --run .test/check_call.php
$info = (object)[
    'name'=>'basic'
];
$fc = function(){
    igk_wln("data : ", $this);
};
$fc = Closure::fromCallable($fc)->bindTo($info);
$fc();
igk_exit();