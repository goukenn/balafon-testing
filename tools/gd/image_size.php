<?php
// get image size 
// @command: balafon --run .test/tools/gd/image_size.php
use IGK\System\Console\Colorize;
use IGK\System\Console\Logger;
$file = igk_getv($params, 0) ?? igk_die('required file path');
if (!file_exists($file)){
    igk_die('missing file');
}
$v_data = file_get_contents($file);
$img = imagecreatefromstring($v_data);
$w = imagesx($img);
$h = imagesx($img);
imagedestroy($img); 
$l = [
    'width'=>$w,
    'height'=>$h
];
$s = json_encode($l, JSON_PRETTY_PRINT);
Logger::SetColorizer(new Colorize);
Logger::print($s); 
igk_exit();