<?php
use IGK\Helper\Utility;
$s = json_decode(Utility::TO_JSON(
    [['info'=>555], 'generate' => date('Ymd His')],
    [
        'ignore_empty' => 1
    ]
    ));
igk_wln_e("s", $s);