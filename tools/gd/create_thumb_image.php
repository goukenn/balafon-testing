<?php
// @command: balafon --run .test/tools/gd/create_thumb_image.php
use IGK\System\Console\Logger;
use IGK\System\IO\Path;
$file = igk_getv($params, 0) ?? igk_die('required file');
// retrieve gd info 
!function_exists('gd_info') && igk_die('missing gd library');
$data = file_get_contents($file);
$image = imagecreatefromstring($data);
$width = imagesx($image);
$height = imagesx($image);
print_r([
    "width"=>$width,
    "height"=>$height
]);
imagedestroy($image);
$ext = igk_io_path_ext($file);
$type = $ext == 'png' ? 1 : 0;
$W = igk_getv($command->options, '--width') ?? 500;
$H = igk_getv($command->options, '--height') ?? 500;
$_scale = igk_getv($command->options, '--scale', 'proportional');
function fit_cover($src, $w, $h, $type = 1, $compression = 0, bool $antialias = false)
    {
        $ih = imagecreatefromstring($src);
        $W = imagesx($ih);
        $H = imagesy($ih);  
        $ex = $w / $W;
        $ey = $h / $H;
        $ex = $ey = max($ex, $ey);
        $x = 0; // intval(ceil(((-$W * $ex) + $w) / 2.0));
        $y = 0; // intval(ceil(((-$H * $ey) + $h) / 2.0)); 
        $img = imagecreatetruecolor($w, $h);
        $black = imagecolorallocate($img, 0, 0, 0);
        imagecolortransparent($img, $black);
        imageantialias($img, $antialias);
        $sh = imagescale($ih, ceil($ex * $W), ceil($ey * $H));
        imagecopy($img, $sh, $x, $y, 0, 0, $w, $h);
        $g = igk_ob_get_func(function ($t) use (&$img, $compression) {
            if ($t == 1) {
                $clevel = 9 - ($compression * 9 / 100);
                echo imagepng($img, null, $clevel);
            } else {
                echo imagejpeg($img, null, $compression);
            }
        }, $type);
        imagedestroy($sh);
        imagedestroy($ih);
        imagedestroy($img);
        return $g; 
}
$l = null;
if ($_scale=='fit-cover'){
    $l = fit_cover($data, $W, $H);
}
else 
    $l = igk_gd_resize_proportional($data, $W, $H, $type, 0, true);
$dir = dirname($file);
$path = Path::Combine($dir, igk_io_basenamewithoutext($file).".thumb.".$ext);
igk_io_w2file($path, $l);
Logger::success('output: '.$path);
igk_exit();