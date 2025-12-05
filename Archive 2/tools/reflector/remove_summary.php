<?php
// @command: balafon --run .test/tools/reflector/remove_summary.php
use IGK\Helper\IO;
use IGK\System\Console\Logger;
use IGK\System\Text\RegexDetectHandler;
$input = igk_getv($params, 0) ?? igk_die('missing parameter');
$contains = property_exists($command->options, '--contains'); // check for contains only 
IO::GetFiles($input, function ($f)use($contains){
        if (preg_match("/\.php|phtml$/", $f)) {
            if (realpath($f) === __FILE__)
                return;
            echo "treat : ".$f."\n"; 
            $src = file_get_contents($f);
            $change = false;
            if ($src != ($ct = igk_str_rm_php_csharp_summary($src))){
                $src = $ct;
                Logger::warn('changed');        
                if (!$contains) {
                    igk_io_w2file($f, $src);
                } else {
                    igk_wln($ct);
                }
            }
        }
    }, true
);
Logger::success('done');
igk_exit();