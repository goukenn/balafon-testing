<?php
// @command: balafon --run .test/regex/replace_begin_end.php
use IGK\System\Text\RegexMatcherUtility;
$src = 'bonjour';
$test = [
    ["begin"=>["", 0, 0], "end"=>["car", 4], 'expected'=>'rajcar']
];
// -2 : njour
igk_wln_e( RegexMatcherUtility::TreatBeginEndCapture($src, '<div>', '</div>', 0, null));