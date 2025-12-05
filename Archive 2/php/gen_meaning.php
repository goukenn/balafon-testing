<?php
for($i = 0; $i < 26; $i++){
    echo "{\"include\":\"#meaning-".chr(ord('a')+$i)."\"},".PHP_EOL;
}