<?php

$data = include(__DIR__ . '/include.php');
foreach ($data as $f) {
    if (basename($f) == 'balafon') continue;
    try {
        \ob_start();
        include_once $f;
        $s = \ob_get_contents();
        \ob_end_clean();
        if (strlen($s) > 0) {
            echo "file : " . $f . " \n";
            exit;
        }
    } catch (TypeError $ex) {
    }
}