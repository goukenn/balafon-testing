<?php
// convert to structured file 
// @command: balafon --run .test/io/convert_to_structured_file.php
use IGK\Helper\IO;
use IGK\System\Console\Logger;
use IGK\System\IO\Path;
($dir = igk_getv($params, 0)) ?? igk_die('missing directory');
$recursive = property_exists($command->options, '--recursive');
$copy_flag = property_exists($command->options, '--copy');
$outs = [];
$fs = IO::GetFiles($dir, "/\.(jp(e)?g|mov|mp4|heic|png|gif|cr2|pdf|tiff)$/i", true);
usort($fs, function ($a, $b) {
    return strtolower($a) <=> strtolower($b);
});
foreach ($fs as $file) {
    $ext = strtolower(trim(igk_io_path_ext($file), '. '));
    $path = $dir . '/' . $ext;
    if (!isset($outs[$ext])) {
        $outs[$ext] = [];
        IO::CreateDir($path);
    }
    $outs[$ext][] = $file;
    $n = str_pad(count($outs[$ext]), 5, '0', STR_PAD_LEFT);
    $outfile = Path::Combine($path, $n . '.' . $ext);
    Logger::print('outfile: ' . $outfile);
    if ($copy_flag){
        copy($file, $outfile);
    } else {
        rename($file, $outfile);
    }
}
echo "\n", json_encode($outs, JSON_PRETTY_PRINT) . "\n";