<?php
use IGK\System\Regex\Replacement;
$regex = Replacement::RegexExpressionFromString($params[0]);
$r = \IGK\Helper\Database::DropTableFromRegex($ctrl, $regex);
if ($r){
    var_dump($r);
}
igk_wln_e("done ");