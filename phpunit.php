<?php
$param = '';
if ($params>0){
    $param = implode (' ', $params);
}
$src =  `phpunit -c phpunit.xml.dist {$param} 2>&2 1>&2`;
echo $src;
igk_exit();