<?php
// @command: balafon --run .test/git/remove-all-tags.php tag_file.txt

$f = igk_getv($params, 0);
$c = '';
$tab = explode("\n", file_get_contents($f));
sort($tab);
foreach($tab as $k){
    if (preg_match('/^v2025\.0/', $k)){
        echo "git tag -d {$k}", PHP_EOL;
    }
}