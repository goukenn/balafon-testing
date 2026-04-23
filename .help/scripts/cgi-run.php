<?php

$dir = IGK_DEV_DIR.'/2023/dotnet' ;
if (!is_dir($dir)){
    igk_die('missing directory');
}
shell_exec('dotnet run --project '.$dir.''
.implode(' ', igk_io_getfiles(IGK_LIB_DIR, "/\.(php|pinc)/", true)))
." 2>&2 " ;