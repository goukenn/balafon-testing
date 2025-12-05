<?php
// @command: balafon --run .test/regex/remove-movement-capture.php
use IGK\System\Console\Logger;
use IGK\System\Text\RegexMatcherContainer;
use IGK\System\Text\RegexMatcherUtility;



        $src = "\\s+((?=(info))|(?<=alpha))";

        $sb = RegexMatcherUtility::RemoveMovementCapture($src);

 preg_match('/'.$sb.'/', 'data    info', $tab);

 Logger::print(json_encode($tab));

 Logger::info('result : '.$sb);
Logger::success('done');
exit;