<?php
// @command: balafon --run .test/reflection/exposed_method.php
/**
 *  @var array $params
 */
$cl = igk_getv($params, 0) ?? igk_die('no defined');
$list = [];
$tmethods = (new ReflectionClass ($cl))->getMethods() ?? $tmethods;
foreach($tmethods as $m){
 if ($m->isPrivate() || $m->isProtected()){
        continue;
    }
    $n = $m->getName().'(';
    $n.= implode(', ', array_map(function($l){
        $s = '';
        if ($l->isDefaultValueAvailable()){
            $s = ' = '.json_encode($l->getDefaultValue());
        }
    return $l->getName().$s;
    }, $m->getParameters()));
    $n.=')';
    if ($m->hasReturnType()){
        $b =$m->getReturnType();
        if ($b instanceof ReflectionUnionType){
        }else{
            $n.=':'.$b->getName();
        }
    }
    if ($doc = $m->getDocComment()){
        $n .="<pre>".$doc."</pre>"; 
    }
    $list[$m->getName()] = $n;
}
echo json_encode($list, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
echo PHP_EOL;