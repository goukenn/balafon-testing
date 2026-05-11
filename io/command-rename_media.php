<?php
// @author: C.A.D. BONDJE DOUE
// @filename: igk_rename_media.php
// @date: 20250903 13:23:57
// @desc: rename media and structure it
// @command: balafon --run [--copy] [--pattern:] .test/io/command-rename_media.php [from] [to]
// @update: add title support
use IGK\Helper\IO;
use IGK\System\Console\Logger;
use IGK\System\IO\Path;
/**
* auto generate doc.
* @var array $params
* @var \IGK\System\Console\ICommandInfo $command
*/

$from = igk_getv($params, 0);
$to = igk_getv($params, 1);
$copy = property_exists($command->options, '--copy');
$pattern = igk_getv($command->options, '--pattern');


if (!function_exists('igk_io_rename_media')) {
    igk_load_library('io');
    /**
    * auto generate doc.
    * @param string $dir
    */
    function igk_io_rename_media(string $dir, ?string $to=null, bool $recursive = false, $copy=false, $pattern=null)
    {
        if ($pattern){
            $pattern = '/' . $pattern . '/i';
        }else{
            $pattern =  "/\.(jp(e)?g|mov|mp(4|3)|heic|png|gif|cr2|pdf|tiff|wmv|avi)$/i";
        }
        $rename = is_callable($copy)? $copy : ($copy ? function($from, $to): bool{
            return copy(
                escapeshellarg($from), escapeshellarg($to)
            );
        } : function($from, $to): bool{
            return rename($from, $to );
        });
        $outs = [];
        $fs = IO::GetFiles($dir, $pattern, $recursive);
        if (!$fs){
            return false;
        }
        usort($fs, function (string $a, string $b) {
            return strtolower($a) <=> strtolower($b);
        });
        if ($is_temp = is_null($to)){
            $to = tempnam($dir,'.local');
            @unlink($to);
            IO::CreateDir($to);
        }
        foreach ($fs as $file) {
            $ext = strtolower(trim(igk_io_path_ext($file), '. '));
            $path = $to . '/' . $ext;
            if (!isset($outs[$ext])) {
                $outs[$ext] = [];
                if (!IO::CreateDir($path))
                    {
                        igk_die('missing or impossible to create a directory');
                    }
            }
            $outs[$ext][] = $file;
            $counter_ext = $ext;
            if (preg_match('/jp(e)?g|heic|cr2|tiff/', $ext)) {
                if ($data = @exif_read_data($file)) {
                    list($datatime_original) = igk_extract($data, 'DateTimeOriginal');
                    if ($datatime_original) {
                        list($date, $time) = explode(' ', $datatime_original, 2);
                        list($year, $month, $day) = explode(":", $date);
                        if ($year) {
                            $path = Path::Combine($path, $year);
                            IO::CreateDir($path);
                            $counter_ext .= ':' . $year;
                            if (!isset($outs[$counter_ext])) {
                                $outs[$counter_ext] = [];
                            }
                            $outs[$counter_ext][] = $file;
                        }
                    }
                }
            }
            $title = '';
            if ($c = igk_io_split_litteral(igk_io_basenamewithoutext($file))){
                $title = '-'.$c->title;
            }


            $n = str_pad(count($outs[$counter_ext]), 5, '0', STR_PAD_LEFT).$title;
            $outfile = Path::Combine($path, $n . '.' . $ext);
            if (file_exists($outfile)) {
                Logger::info('outfile exists: ' . $outfile);
            } else {
                Logger::print('outfile: ' . $outfile);
                $rename($file, $outfile);
            }
        }
        if ($is_temp){
            $dirs = IO::GetList($to);
            $unlink = true;
            while(count($dirs)>0){
                $g = array_shift($dirs);
                $of = Path::Combine($dir, basename($g));
                $merge = false;
                if (is_dir($of)){
                    if (!@unlink($of)){
                        $files = IO::GetFiles($g, '/.$/',true); 
                        foreach($files as $ff){
                            $cg = substr($ff, strlen($to));
                            $path = Path::Combine($dir, $cg);
                            IO::CreateDir(dirname($path));
                            $rename($ff, $path);
                        } 
                        $merge =true;
                    }
                }
                if (!$merge)
                $rename($g,$of); 
            }
            if ($unlink)
                IO::RmDir($to);
        }
        return $outs;
    }
}
igk_io_rename_media($from, $to, true, $copy, $pattern);
Logger::success("done");
igk_exit();