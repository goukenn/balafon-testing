<?php
// @command: balafon --run .test/js/detect.import.php
// @description: detect import outside
$pattern = '/^\s*import\s+(([\w{}*\n\r\t, ]+)\s+from\s+)?([\'"])(?P<path>[^\'"]+)\\3\s*(;|\n)/m';
$src = <<<'JS'
import
 * as L 
from 'data'
import * as M from "da\"ta";
import "data.css"

JS;
$c = preg_match_all($pattern, $src, $tab);
if ($c){
    for($i = 0; $i < $c; $i++){        
        $src = str_replace($tab[0][$i], '', $src);
    }
}
igk_wln_e($tab, $src);