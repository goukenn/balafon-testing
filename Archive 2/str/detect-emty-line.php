<?php
// @command: balafon --run .test/str/detect-emty-line.php
$src = implode("\n", [
    'line 1',
    '      ',
    'line 2',
    '',
    ' o',
    'line 3',
    ''
]);
$regex = "/^\\s*?\\n/m"; // +| will capture the last \n line feed char 
$regex = "/^\\s*?$/m";// + | will ignore the last line feed char 
// last empty line will not be detected
$c = preg_match_all($regex, $src, $tab, PREG_OFFSET_CAPTURE);
igk_wln_e($c, json_encode($tab, JSON_PRETTY_PRINT));