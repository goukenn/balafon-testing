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
$exclude_dir = [
    ".Caches",
    "node_modules",
    ".git",
    ".vscode",
    "vendor",
    "/Volumes/Data/Dev/PHP/balafon_site_dev/src/sesstemp",
    "/Volumes/Data/Dev/PHP/balafon_site_dev/src/application/.Caches",
    "/Volumes/Data/Dev/PHP/balafon_site_dev/src/application/Data",
    "/Volumes/Data/Dev/PHP/balafon_site_dev/src/application/Projects/CarRental/Data/store",
    "/Volumes/Data/Dev/PHP/balafon_site_dev/src/application/Projects/AppBalafon/Data/backup/balafon",
    "/Volumes/Data/Dev/PHP/balafon_site_dev/src/public/assets/_chs_",
    "/Volumes/Data/Dev/PHP/balafon_site_dev/src/public/assets/_lib_",
    "/Volumes/Data/Dev/PHP/balafon_site_dev/src/public/assets/_prj_",
    "/Volumes/Data/Dev/PHP/balafon_site_dev/src/public/assets/_mod_",
    "/Volumes/Data/Dev/PHP/balafon_site_dev/src/public/module",
    "/Volumes/Data/Dev/PHP/balafon_site_dev/src/public/test_inclusion",
    "/Volumes/Data/Dev/PHP/balafon_site_dev/src/public/ttr-dashboard",
    "/Volumes/Data/Dev/PHP/balafon_site_dev/src/public/woh",
    "/Volumes/Data/Dev/PHP/balafon_site_dev/src/public/swagger",
    "/Volumes/Data/Dev/PHP/balafon_site_dev/src/public/swagger2",
    "/Volumes/Data/Dev/PHP/balafon_site_dev/src/public/phpmyadmin",
    "/Volumes/Data/Dev/PHP/balafon_site_dev/src/public/daw",
    "/Volumes/Data/Dev/PHP/balafon_site_dev/src/public/app",
    "/Volumes/Data/Dev/PHP/balafon_site_dev/src/public/webgrind",
    "/Volumes/Data/Dev/PHP/balafon_site_dev/src/public/auth",
    "/Volumes/Data/Dev/PHP/balafon_site_dev/src/public/demos",
    "/Volumes/Data/Dev/PHP/balafon_site_dev/src/public/winui",
    "/Volumes/Data/Dev/PHP/balafon_site_dev/src/public/wordpress",
    "/Volumes/Data/Dev/PHP/balafon_site_dev/src/application/Projects/AppBalafon/Data/store",
];
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
            (dirname($f) == '/Volumes/Data/Dev/PHP/balafon_site_dev/src/public') && (!preg_match('/\\b(index\.php)\\b/', $bname))
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