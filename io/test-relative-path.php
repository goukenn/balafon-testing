<?php
// @command: balafon --run .test/io/test-relative-path.php
use IGK\System\Console\Logger;

$ls = json_decode(<<<JSON
[{
    
        "source":"/",
        "target":"/",
        "response":"./"
},
{
    
        "source":"/var/dev/python/sample/src/application/Lib/igk",
        "target":"/var/dev/python/sample/src/Lib/igk",
        "response":"../../Lib/igk"
},
{
    
        "source":"./src/application/Lib/igk",
        "target":"./src/Lib/igk",
        "response":"../../Lib/igk"
}]
JSON);
foreach($ls as $p=>$f){
    $g= \IGK\System\IO\Path::GetRelativePath($f->source, $f->target);
    igk_wln($g );
    if ($g == $f->response){
        Logger::success("success");
    } else {
        Logger::danger('faild');
    }
}