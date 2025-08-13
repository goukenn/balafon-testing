<?php
// @command: balafon --run .test/tools/gen_web_component.php
use IGK\System\Html\Dom\HtmlNode;
use IGK\System\IO\StringBuilder;
$nodes = explode('|', HtmlNode::NODE_LIST);
$components = [];
$sb = new StringBuilder;
$uses = [];
$skip = ['var'];
$funcs = [];
$list = [];
foreach($nodes as $k){
    if (in_array($k, $skip))
        continue;
    if (function_exists($fc = 'igk_html_node_'.$k)){
        $uses[$k] = sprintf('use function %s as _%s;', $fc, $k);
        $list[$k] = implode("\n", [
            sprintf('if (!function_exists("%s")){', $k),
            'function '.$k.'(){',
                sprintf('return _%s(...func_get_args());',$k),
                '}',
            '}'
        ]);
        $functs[$fc] = 1;
    }else{
        $list[$k] = (implode("\n", [
            sprintf('if (!function_exists("%s")){', $k),
            'function '.$k.'(){',
                sprintf('return igk_create_node("%s", null, func_get_args());',$k),
                '}',
            '}'
        ]));
    }
}
$r = igk_getv(get_defined_functions(true), 'user');
foreach($r as $k){
    if (!preg_match("/^igk_html_node_(.+)/", $k, $tab))continue;
    if (isset($funcs[$k])){
        continue;
    }
    $name = $tab[1];
    if (preg_match("/\\b(if|clone|include|list|yield)\\b/", $name)){
        $name .= "_node";
    }
    $list[$name] = (implode("\n", [
        sprintf('if (!function_exists("%s")){', $name),
        'function '.$name.'(){',
            sprintf('return %s(...func_get_args());',$k),
            '}',
        '}'
    ]));
} 
ksort($list);
$sb->appendLine(implode("\n", $list));
echo "<?php", PHP_EOL;
echo implode("\n", $uses), PHP_EOL;
echo $sb.'';
igk_exit();