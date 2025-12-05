<?php
// balafon --run .test/php/closure_reflexion.php
use igk\io\GraphQl\GraphQlQueryOptions;
use IGK\Models\ModelBase;
use IGK\Models\Users; 
$c = function (Users $user, array $def, & $x=12, int $j=JSON_PRETTY_PRINT, ?GraphQlQueryOptions $option=null){
};
$g = new ReflectionFunction($c);
$c = $g->getNumberOfParameters();
igk_wln("number of require parameter : ", $c);
if ($c){
    // + | --------------------------------------------------------------------
    // + | get parameter dispatche info
    // + |    
    $params = [];
    $v_is_v8 = version_compare(PHP_VERSION, '8.0', '>=');
    $callable = null;
    foreach($g->getParameters() as $info){
        $n = $info->getName();
        $t = null;
        $d = null;
        if ($info->hasType()){
            $t = $info->getType()->getName(); 
        }
        $p = [];
        if ($info->isDefaultValueAvailable()){
            $p['default'] = $info->getDefaultValue();
            if ($info->isDefaultValueConstant()){
                $p['ctn'] = $info->getDefaultValueConstantName();
            }
        }
        $p['is_optional'] = $info->isOptional();
        $p['is_ref'] = $info->isOptional();
        $p['is_variadic'] = $info->isVariadic();
        $p['is_promoted'] = $v_is_v8 ? $info->isPromoted() : false;
        $p['allow_null'] = $info->allowsNull();  
        $params[$n] = (object)array_merge(['type'=>$t], $p); 
        // if callable - map data
        if ($callable){
            $callable($params[$n]);
        };
    }
    print_r($params);
}
interface ISource{
    function doFoo();
}
class B implements ISource{
    function doFoo(){
        return 'call foo on '.__CLASS__;
    }
}
 echo " ============= ".igk_is_class_assignable(ModelBase::class, ModelBase::class).PHP_EOL;
 echo " ============= ".igk_is_class_assignable(B::class, ISource::class).PHP_EOL;
print_r($g);
igk_exit();