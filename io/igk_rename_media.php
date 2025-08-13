<?php
// @command: balafon --run .test/io/igk_rename_media.php [from] [to]
use IGK\Helper\IO;
use IGK\System\Console\Logger;
use IGK\System\IO\Path;
$from = igk_getv($params, 0);
$to = igk_getv($params, 1);
$copy = property_exists($command->options, '--copy');
if (!function_exists('igk_io_rename_media')) {
    /**
     * @param string $dir
     * @param string $to destination folder 
     * @param string $dir
     * @param string $dir
     */
    function igk_io_rename_media(string $dir, ?string $to=null, bool $recursive = false, $copy=false)
    {
        $rename = is_callable($copy)? $copy : ($copy ? function($from, $to){
            return copy(
                $from, $to
            );
        } : function($from, $to){
            return rename($from, $to);
        });
        $outs = [];
        $fs = IO::GetFiles($dir, "/\.(jp(e)?g|mov|mp4|heic|png|gif|cr2|pdf|tiff)$/i", $recursive);
        if (!$fs){
            return false;
        }
        usort($fs, function ($a, $b) {
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
                IO::CreateDir($path);
            }
            $outs[$ext][] = $file;
            $counter_ext = $ext;
            if (preg_match('/jp(e)?g/', $ext)) {
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
            $n = str_pad(count($outs[$counter_ext]), 5, '0', STR_PAD_LEFT);
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
                        // because folder is not empty merge 
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
                $rename($g,$of); //Path::Combine($dir, basename($g)));
            }
            if ($unlink)
                IO::RmDir($to);
        }
        return $outs;
    }
}
igk_io_rename_media($from, $to, true, $copy);
Logger::success("done");
igk_exit();