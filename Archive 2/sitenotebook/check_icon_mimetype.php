<?php
// @command: balafon --run .test/sitenotebook/check_icon_mimetype.php
// @desc: get file info detected mime type 
$sr = file_get_contents(__DIR__.'/output/looka.com/favicon.ico');
$file = new finfo(FILEINFO_MIME);
echo $file->buffer($sr), "\n";
igk_exit();