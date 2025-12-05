<?php
// @command: balafon --run .test/benchmark/include_vs_filecontents.php
use IGK\System\Console\Logger;
igk_start_time(__FILE__);
ob_start();
for($i = 0; $i < 100000; $i++){
    file_get_contents(__DIR__.'/inc.php');
}
ob_end_clean();
igk_wln('file_get_contents: '.igk_execute_time(__FILE__));
igk_start_time(__FILE__);
ob_start();
for($i = 0; $i < 100000; $i++){
    include(__DIR__.'/inc.php');
}
ob_end_clean();
igk_wln('include: '.igk_execute_time(__FILE__));
Logger::danger('include is slower than file_get_contents');