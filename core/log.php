<?php
use IGK\System\Console\Logger;

if ($lf = IGKLog::GetSystemLogFile()){
    Logger::warn('unlink : '.$lf);
    echo "? ".@unlink($lf); 
}
igk_ilog('data:');