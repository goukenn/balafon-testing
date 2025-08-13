<?php
use IGK\System\Exceptions\ArgumentTypeNotValidException;
/**
 * support traits
 * @param mixed $obj_or_class 
 * @param mixed $trait 
 * @return bool 
 * @throws IGKException 
 * @throws ArgumentTypeNotValidException 
 * @throws ReflectionException 
 */
function support_trait($obj_or_class, $trait): bool{
    if (!trait_exists($trait, false)){
        return false;
    }
    $trait_cl = [$obj_or_class];
    while(count($trait_cl)>0){
        $q = array_shift($trait_cl);
        if ($g = class_uses($q)){
            if (in_array($trait, $g)){
                return true;
            }
        }
        if (($s = igk_sys_reflect_class($q)->getParentClass()) && ($s = $s->getName())){
            array_push($trait_cl, $s);
        }
    }
    return false;
}
$g = bantubeatController::ctrl();
$g::register_autoload();
$cl  = $g->resolveClass(\Actions\ApiAction::class);
if ($cl){
    echo "support : ". support_trait($cl , \IGK\Actions\Traits\ApiActionTrait8::class);
} else {
    echo "failed";
}
exit;