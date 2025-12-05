<?php
// @command: balafon --run .test/png/encode_chunk.php
$data = file_get_contents('pics.png'); 
$sep="\r\n".chr(32);
echo rtrim(chunk_split('PHOTO;ENCODING=b:'.base64_encode($data),75, $sep), $sep), PHP_EOL;
exit;