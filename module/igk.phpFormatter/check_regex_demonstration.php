<?php
// @command: balafon --run .test/module/igk.phpFormatter/check_regex_demonstration.php
$src = 'if (true)';
$regex = '/(?<=\))\\s*(?=\{|;)/';
$tab = [];
$pos = strpos($src,')')+1;
echo false === preg_match($regex, $src, $tab, PREG_OFFSET_CAPTURE, 0), PHP_EOL;
echo $pos, PHP_EOL;
print_r($tab);
igk_exit();
