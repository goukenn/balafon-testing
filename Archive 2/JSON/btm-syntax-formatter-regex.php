<?php
// btm-syntax regex formatter with data
use igk\btmSyntax\Formatter;
$v_formatter = Formatter::CreateFrom(json_decode(file_get_contents(__DIR__."/demo.btm-syntax.json")));
echo $v_formatter->format("pascal");
igk_exit();