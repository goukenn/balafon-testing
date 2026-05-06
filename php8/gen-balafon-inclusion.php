<?php
// @author: C.A.D. BONDJE DOUE
// @filename: anonymous_class.php
// @date: 20251209 10:22:22
// @desc:  generate inclusion 
// @command: balafon --run .test/php8/anonymous_class.php


$c = get_included_files();
array_pop($c);
echo "\n";
echo "return ".json_encode($c, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . ";".PHP_EOL; 
igk_exit();