<?php
// @author: C.A.D. BONDJE DOUE
// @filename: zip_site.php
// @date: 20250913 16:16:21
// @desc: zip site
// @command: balafon --run .test/tools/zip_site.php
// @balafon-command: zipsite
use IGK\Helper\IO;
use IGK\System\Console\Logger;

$dir = igk_getv(
    $params,
    0
) ?? dirname(igk_io_basedir());
$v_odir = igk_getv(
    $params,
    1
) ?? 'out.zip';
$exclude_dir = array_merge([
    ".Caches",
    "node_modules",
    ".git",
    ".vscode",
    "vendor",
], (function($command){
    if ($m= igk_getv($command->options, '--exclude')){
        if (!is_array($m)){
            $m = [$m];
        }
    }
  return $m ?? [];  
})($command));
$zip = new ZipArchive();
if ($zip->open($v_odir, ZipArchive::OVERWRITE | ZipArchive::CREATE)) {
    $entry = [];
    $ln = rtrim(strlen($dir), '/') + 1;
    $T = 0;
    IO::GetFiles($dir, function ($f) use ($zip, &$entry, $ln, &$T) {
        $bname = basename($f);
        if (preg_match('/(\.DS_Store)/', $bname)) {
            return;
        }
        if (
            (dirname($f) == getenv('IGK_SITE_DEV_DIR').'/src/public') && (!preg_match('/\\b(index\.php)\\b/', $bname))
        ) {
            return;
        }
        if (($bf = realpath($f)) != $f) {
            if (false === $bf) {
                return;
            }
            if (isset($entry[$bf])) {
                return;
            }
            $entry[$bf] = 1;
        }
        $c = substr($f, $ln);
        if (is_file($bf)) {
            $fsize = filesize($f);
            if ($fsize > 1000000) {
                Logger::danger("greater ".$f);
            }
            igk_debug_wln($f . ":" . $fsize);
            $T += $fsize;
            $zip->addFile($bf, $c);
        }
        return false;
    }, true, $exclude_dir);
    Logger::info('close zip...');
    $zip->close();
    $fsize = filesize($v_odir);
    echo json_encode([
        "TOTAL TO COMPRESS" => $T,
        "Size" => $fsize,
        "compression" => sprintf('%s%%', round((100 * $fsize) / $T)),
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES), PHP_EOL;
}
Logger::success('complete');
igk_exit(1, 0);