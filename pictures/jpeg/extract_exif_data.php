<?php
// @command: balafon --run .test/pictures/jpeg/extract_exif_data.php [file]
($file = igk_getv($params, 0)) ?? igk_die('missing required file');
if (file_exists($file)) {
    if ($data = @exif_read_data($file)) {
        list($datatime_original) = igk_extract($data, 'DateTimeOriginal');
        if ($datatime_original) {
            list($date, $time) = explode(' ', $datatime_original, 2);
            list($year, $month, $day) = explode(":", $date);
            igk_wln_e($data);
        }
    }
}