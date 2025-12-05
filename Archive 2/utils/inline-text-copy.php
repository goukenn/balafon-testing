<?php
// @command: balafon --run .test/utils/inline-text-copy.php
function getCliboard(){
    $_os = strtolower(PHP_OS);
    if ($_os =='darwin')
        return substr(`pbpaste`, 0, -1);
} 
$data = getCliboard();
if ($data)
echo base64_encode($data), PHP_EOL;;
igk_exit();