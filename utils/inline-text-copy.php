<?php
// @command: balafon --run .test/utils/inline-text-copy.php

/**
* auto generate doc.
*/
function getCliboard(){
    $_os = strtolower(PHP_OS);
    if ($_os =='darwin')
        return substr(shell_exec("pbpaste"), 0, -1);
} 
$data = getCliboard();
if ($data)
echo base64_encode($data), PHP_EOL;;
igk_exit();