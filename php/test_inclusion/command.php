<?php
echo 'inclusion', PHP_EOL;
ob_start();
include __DIR__.'/inc';
$c = ob_get_contents();
$tab = explode("\n", $c, 2);
array_shift($tab);
ob_end_clean();
echo ":::::".$tab[0];