<?php
// @command: balafon --run .test/scripts/mame-games/cps-to-mame.php
use IGK\Helper\IO;
use IGK\System\Console\Logger;
use IGK\System\IO\Path;

Logger::print('convert cps rom to mame-compatibility');

$in = igk_getv($params, 0) ?? __DIR__.'/cps-1';
$out = igk_getv($params, 1) ?? __DIR__.'/out-mame/roms';


function convertToMame($file, $out){
    $n = igk_io_basenamewithoutext($file); 
    $dir = igk_io_tempdir('roms');
    echo $dir , PHP_EOL; Logger::info('unzip archive');
    igk_zip_unzip($file, $dir);
    copy(__DIR__.'/qsound.bin', $dir.'/qsound.bin');

    $zip = new ZipArchive();
    $ofile = Path::Combine($out, $n.'.zip');
    IO::CreateDir(dirname($ofile));
    
    if ($zip->open($ofile, ZipArchive::CREATE | ZipArchive::OVERWRITE)){
        igk_zip_dir($dir, $zip, null, null, true);
       // rename($dir, $ofile = Path::Combine($out, $n.'.zip'));

        $zip->close();
        Logger::info('outfile: '.$ofile);
    }
    IO::RmDir($dir,true);
}


foreach(IO::GetFiles($in, '/\.zip/') as $f){
    convertToMame($f, $out);
}
Logger::success('done');
igk_exit();