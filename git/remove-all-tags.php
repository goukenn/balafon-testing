<?php
// @command: balafon --run .test/git/remove-all-tags.php
$f = '/Volumes/Data/Dev/PHP/balafon2/ctags.txt';
$c = '';
$tab = explode("\n", file_get_contents($f));
sort($tab);
foreach($tab as $k){
    // echo $k, PHP_EOL;
    if (preg_match('/^v2025\.0/', $k)){
        echo "git tag -d {$k}", PHP_EOL;
       // echo `git push --delete https://github.com/goukenn/igkdev-balafon.git {$k}`;
    }
}