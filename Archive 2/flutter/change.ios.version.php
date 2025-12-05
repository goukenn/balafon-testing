<?php
// @command: balafon --run .test/flutter/change.ios.version.php
use igk\Google\flutter\IOSUtility;
$file = '/Volumes/Data/WillOnHair/com.igkdev.app.willonair/ios/Runner.xcodeproj/project.pbxproj';
$version = '15.6';
$src = IOSUtility::ChangeIOSVersion($file, $version);
igk_wln_e($src);