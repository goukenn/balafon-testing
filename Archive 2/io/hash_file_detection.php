<?php
 // utility script: remove file in directory if a copy of the same file exists
 // @command: balafon --run .test/io/hash_file_detection.php
use IGK\Helper\IO;
use IGK\System\Console\Logger;
use IGK\System\IO\Path;
// $dir = '/Volumes/Data/Media/Pictures';
// $fs = IO::GetFiles($dir,"/\.(mov|mp4|avi)$/i", true);
// IO::CreateDir($d = $dir."/movies_out");
// $i = 0;
// foreach($fs as $file){
//     $n = str_pad($i, 5, '0', STR_PAD_LEFT);
//     $path = Path::Combine($d, $n .'.'.igk_io_path_ext($file));
//     rename($file, $path);
//     $i++;
// }
// igk_wln($fs);
// igk_exit();
($dir = igk_getv($params, 0)) ?? igk_die('required folder'); // 
$outs = [];
$fs = IO::GetFiles($dir,"/\.(jp(e)?g|mov|mp4|heic|png|gif|cr2|pdf)$/i", true);
usort($fs, function($a, $b){
    return strtolower($a)<=>strtolower($b);
});
$list = [];
$T = count($fs);
$i = 0;
$d = 0;
foreach($fs as $file){
    $ck = hash_file('sha256', $file, false);
    if (!isset($list[$ck])){
        $list[$ck] = $file;
    }else{
        igk_wln('same - hash - on ' . $file );
        igk_wln('source hash: '. $list[$ck]);
        @unlink($file);
        $d++;
    }
    $i++;
    echo $i." / ".$T."\r";
}
 echo "\n", json_encode($outs, JSON_PRETTY_PRINT) . "\n";
 Logger::success("remove duplicated : ". $d);
igk_exit();