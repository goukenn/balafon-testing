<?php
// @command: balafon --run .test/io/search-for-file.php directory [strstr_match]
// search for classes library files 
use IGK\Helper\IO;
use IGK\System\Console\Logger;
($dir = igk_getv($params, 0) ) || igk_die('missing file');
Logger::print("searching for file:");
$g = igk_getv($params, 1) ??  "Lib/Classes/Database/";
foreach(IO::GetFiles($dir, "/\.php$/", true) as $f){
    if (strstr($f, $g)){
        Logger::print($f);
    }
}
echo "finish", PHP_EOL;
exit;