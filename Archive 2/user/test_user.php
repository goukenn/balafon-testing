<?php
// register dev user
use IGK\Models\Users;
echo 'environment: '.igk_environment()->isOPS() .PHP_EOL;
if ($u = Users::Register(['clLogin'=>"cbondje@igkdev.com"])){
    $u->changePassword($params[0]);
    $u->activate();
    echo $u->to_json();
}
exit;