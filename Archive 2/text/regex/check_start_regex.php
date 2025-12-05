<?php
use IGK\System\Console\App;
use IGK\System\Text\RegexMatcherContainer;
$tab = ['/^hello/',
'/(^)?hello/',
'/hello/',
'/(hello|^hi)/', 
'/(\^)hi)/', 
'/sam^ple/', 
'/sam[^m]le/', 
'/(?=;|^\w+)/'
];
// regex start with ^ start line check
function checkRegex($tab){
    $regex = '/(?<!\\\\|\w|\[)\^/';
    foreach($tab as $n){
        igk_wln( $n . ' = '.igk_parsebool(preg_match($regex, $n)));  
    }
}
ob_start();
checkRegex($tab);
$c = ob_get_contents();
ob_end_clean();
$container = new RegexMatcherContainer;
$container->match('\b(true|false)\b', 'bool');
$container->match('(=|\+(\+)|%)', 'operator');
$container->appendStringDetection();
$output = '';
$tokens = [
    'string'=>App::BLUE,
    'operator'=>App::AQUA
];
$lpos = 0;
$container->treat($c, function($e, & $next_pos, $data)use(& $output, $tokens, & $lpos){
    $color = igk_getv($tokens, $e->tokenID) ?? App::SHA_INDIGO;
    $output.= substr($data, $lpos, $e->from - $lpos).sprintf('%s%s%s',$color, $e->value, App::END);
    //$next_pos = 0;
    $lpos = $next_pos;
});
echo $output."\n";
igk_exit();