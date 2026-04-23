<?php
// @command: balafon --run .test/modules/igk_windows_rtf/gen_book_store.php
// @author: C.A.D. BONDJE DOUE
// @filename: gen_book_store.php
// @date: 20260129 13:32:59
// @desc: gen books store 
use IGK\Helper\IO;
use IGK\Helper\StringUtility;
use IGK\System\Console\Logger;
use IGK\System\IO\Path;

(php_sapi_name() != 'cli') && igk_die('run in cli please');
$prefixes = ['annexes_','chapter_', 'introduction_', 'preface_'];
$clean = property_exists($command->options, '--clean' );
$outdir = igk_getv($command->options, '--outdir' ) ?? __DIR__.'/output';
$titles = [
    "Introduction"
];
if ($clean)
    IO::RmDir($outdir);
foreach(range(1,10) as $l){
    $n = $prefixes[1].($l).'_'.StringUtility::Slugify(igk_getv($titles, $l-1, "Balafon - Chapter")).'.md';
    igk_io_w2file(Path::Combine($outdir, $n), "# Chapter ".$l. ". : ISDF");
}
Logger::success('done');
igk_exit();