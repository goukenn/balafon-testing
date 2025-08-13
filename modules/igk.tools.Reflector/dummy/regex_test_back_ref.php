<?php
// @command: balafon --run .test/modules/igk.tools.Reflector/dummy/regex_test_back_ref.php
$src = "info  ;";

// because of the look behind compilation error. 
$c = preg_match("/(?<=info)(?=\s*;)/", $src, $tab);

print_r(json_encode($tab, JSON_PRETTY_PRINT));
exit;
