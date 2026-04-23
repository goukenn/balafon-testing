<?php
// @command: balafon --run .test/core/dispatcher-parameters.php
use IGK\Actions\Dispatcher;
use IGK\Models\Users; 
use IGK\System\Http\Request;

/**
* auto generate doc.
*/
class DoSome
{
    /**
    * auto generate doc.
    * @param Request $request
    * @param string $i
    * @param null|Users $user
    */
    public function R(Request $request, string $i, ?Users $user) {
    }
}
$cl = igk_sys_reflect_class(DoSome::class);
$parameters = $cl->getMethod('R')->getParameters();
$arguments = Dispatcher::GetInjectArgsByParameters($parameters, [ 7, 1]);
var_dump($arguments);
exit;
/**
* auto generate doc.
* @param array $parameters
* @param array $args
*/
function igk_params_list(array $parameters, array  $args)
{
    $out = [];
    $i = 0;
    $next = false;
    /**
    * auto generate doc.
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
$list = []; 
igk_wln_e($arguments, $list);