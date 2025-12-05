<?php
// @author: C.A.D. BONDJE DOUE
// @filename: treat_files.php
// @date: 20250903 13:21:12
// @desc: utility script-remove file in directory if a copy of the same file exists
// @command: balafon --run .test/utils/macos/photos/remove_duplicates.php
use IGK\Helper\IO;
use IGK\System\Console\Logger;

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