<?php


// @command: balafon --run .test/core/dispatcher-parameters.php

use IGK\Actions\Dispatcher;
use IGK\Models\Users; 
use IGK\System\Http\Request;

class DoSome
{
    public function R(Request $request, string $i, ?Users $user) {
        
    }
}
// Logger::info('-----------------------|-------------------------------------------');
$cl = igk_sys_reflect_class(DoSome::class);
$parameters = $cl->getMethod('R')->getParameters();

// igk_environment()->set('debug/dispatcher', true);
$arguments = Dispatcher::GetInjectArgsByParameters($parameters, [ 7, 1]);


var_dump($arguments);
exit;





function igk_params_list(array $parameters, array  $args)
{
    $out = [];
    $i = 0;
    $next = false;
    /**
     * @var ReflectionProperty $p
     */
    foreach ($parameters as $p) {
        $v = igk_getv($args, $i);
        $next = true;
        if ($p->hasType()) {
            $type = $p->getType()->getName();
            $injectable = IGKType::IsInjectable($type);
            if ($injectable) {
                $n = true;
                if ($n = ($v && is_object($v))){
                    $cl = get_class($v);
                    if (!($cl == $type) && !(is_subclass_of($cl, $type))) {
                        $n = false;
                    }
                }
                if (!$n) {
                    $v = null;
                    $next = false;
                }
            }
        }
        if ($next) {
            $i++;
            igk_wln('move next');
        }
        $out[] = $v;
    }

    return $out;
}


igk_environment()->set('debug/dispatcher', true);
$arguments = Dispatcher::GetInjectArgsByParameters($parameters, [ 7, 1]);

$list = []; // igk_params_list($parameters, [$user, 1]);
igk_wln_e($arguments, $list);
