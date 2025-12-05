<?php
use IGK\Helper\JSon;
use IGK\Helper\JSonEncodeOption;
$c = new JSonEncodeOption;
$c->ignore_empty = true;
$c->ignore_null = true;
$c->allow_key_assoc_empty_array = true;
echo JSon::Encode((object)[
    ["list"=>[
    "security"=>[
        (object)["type"=>[]],
        (object)["auth"=>[]],
        (object)["jump"=>null]
    ]]]
    ],$c, JSON_PRETTY_PRINT);
igk_exit();