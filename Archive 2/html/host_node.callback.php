<?php
// @command: balafon --run .test/html/host_node.callback.php
use function igk_html_host as _h;
$g = _h('div > host', [function($a){
    $a->div()->Content = 'Hello';
}]);
echo $g->render();
exit;