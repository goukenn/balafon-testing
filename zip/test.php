<?php
use IGK\Helper\IO;
$out = __DIR__."/ouput";
IO::CreateDir($out);
igk_zip_unzip(__DIR__."/.test.zip", $out); 
echo "complete";
exit;