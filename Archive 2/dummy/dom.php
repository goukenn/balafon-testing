<?php

$list = [
    "tomates","mangues", "avocats"
];

rsort($list);

foreach($list as $k){
    echo strtolower($k), PHP_EOL;
}

