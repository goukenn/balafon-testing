<?php
// @command: balafon --run .test/markdownw/order.php
use IGK\System\Console\Logger;

$n = igk_create_notagnode();
$n->markdown(implode("\n", [
        "# h1",
        "> info",
        "---"
]));
$s = $n->render();
echo $s;
igk_assert_die(
    $s !=
        '<div class="md-doc"><p>a</p><h1>b</h1><p>c</p><h1>d</h1><p>e</p></div>',
    'missing order definition'
);
Logger::info('done');
$n->renderAJX();
igk_exit();