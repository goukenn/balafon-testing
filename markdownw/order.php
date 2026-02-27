<?php
// @command: balafon --run .test/markdownw/order.php
use IGK\System\Console\Logger;
// $a = " sdfa ";
// $b = & $a;
// $b = & (0);
// // unset($b); // release pointer 
// $b = "233";
// echo $a;
// exit;
$n = igk_create_notagnode();
$n->markdown(implode("\n", [
        // "- printing demonstration",
        //     "- left",
        "# h1",
        "> info",
        "---"
//  "** b ** info",
//             "du jour ",
//             "- b ",
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