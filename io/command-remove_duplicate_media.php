<?php
// @author: C.A.D. BONDJE DOUE
// @filename: command-remove_duplicate_media.php
// @date: 20260506 09:43:18
// @desc: remove duplicate medias
// @command: balafon --run .test/io/command-remove_duplicate_media.php [dir]
use IGK\Helper\IO;
use IGK\System\Console\Logger; 

/**
 * @var array $params
 * @var stdClass $command
 */

($dir = igk_getv($params, 0)) ?? igk_die('required folder');
if (igk_getv($command->options, '--help')) {
    Logger::print('remove duplicate files ');
    return;
}
$outs = [];
$fs = IO::GetFiles($dir, "/\.(jp(e)?g|mov|mp4|heic|png|gif|cr2|pdf)$/i", true);
usort($fs, function ($a, $b) {
    return strtolower($a) <=> strtolower($b);
});
$list = [];
$T = count($fs);
$i = 0;
$d = 0;
$s = '';
foreach ($fs as $file) {
    $ck = hash_file('sha256', $file, false);
    if (!isset($list[$ck])) {
        $list[$ck] = $file;
    } else {
        igk_wln('same - hash - on ' . $file);
        igk_wln('source hash: ' . $list[$ck]);
        @unlink($file);
        $d++;
        $s = sprintf(' - %5s', $d);
    }
    $i++;
    echo $i . " / " . $T . $s . "\r";
}
echo "\n", json_encode($outs, JSON_PRETTY_PRINT) . "\n";
Logger::success("remove duplicated : " . $d);
igk_exit();
