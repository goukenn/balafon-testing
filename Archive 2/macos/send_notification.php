<?php
// @command: balafon --run .test/macos/send_notification.php
use IGK\System\Console\Logger;
$title = escapeshellarg('Sample notify ');
$message = escapeshellarg('f');
$command = "osascript -e 'display notification $message with title $title'";
exec($command);
Logger::success('done');
igk_exit();