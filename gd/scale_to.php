<?php
// @command: balafon --run .test/gd/scale_to.php
use IGK\Helper\IO;
use IGK\System\IO\Path;
$file = igk_getv($params, 0) ?? igk_die('missing file');
$size = igk_getv($params, 1) ?? '1320x2868';
$compression = intval(igk_getv($params, 2) ?? 8);
list($W, $H) = igk_extract(explode("x", $size), '0|1');
if (is_null($H)){
    $H = $W;
}
$W = intval($W);
$H = intval($H);
$dir = property_exists($command->options, '--dir');
if ($dir || is_dir($file)){
    $list = IO::GetFiles($file, '/\.(jp(e)?g|png)$/');
} else {
    $list = [$file];
}
while(count($list)>0){
    $prefix = 'transform.';
    $file = array_shift($list); 
    if (igk_str_startwith(basename($file), $prefix)){
        continue;
    }
    $c = igk_gd_resize_proportional(file_get_contents($file), $W, $H, 0, $compression , false, true,false );    
    igk_io_w2file(Path::Combine(dirname($file), $prefix.basename($file)), $c);
}
    exit;