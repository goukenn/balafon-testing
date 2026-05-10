<?php
// @command: balafon --run .test/macos/send_notification.php
use IGK\System\Console\Logger;

$title = igk_str_surround(escapeshellcmd('Sample notify '));
$message = igk_str_surround(escapeshellcmd('f'));


$command = "osascript -e 'display notification $message with title $title'";
exec($command);
Logger::success('done');
igk_exit();